<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\VoucherModel;
use Illuminate\Http\Request;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Helpers\Promo\PromoHelper;
use App\Helpers\Sales\SalesHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Customer\CustomerHelper;
use App\Http\Requests\Sales\SalesRequest;
use App\Helpers\Report\SalesCategoryHelper;
use App\Helpers\Report\SalesCustomerHelper;
use App\Http\Resources\Sales\SalesResource;
use App\Http\Resources\Sales\SalesCollection;

class SalesController extends Controller
{
    private $sales;
    private $salesCategory;
    private $salesCustomer;
    private $customers;
    private $promo;

    public function __construct()
    {
        $this->sales = new SalesHelper();
        $this->salesCategory = new SalesCategoryHelper();
        $this->salesCustomer = new SalesCustomerHelper();
        $this->customers = new CustomerHelper();
        $this->promo = new PromoHelper();
    }

    public function index(Request $request)
    {
        $filter = [
            'm_customer_id' => $request->m_customer_id ?? '',
            'm_product_id' => $request->m_product_id ?? '',
        ];

        $sales = $this->sales->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new SalesCollection($sales['data']));
    }
    public function store(SalesRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'customer_id',
            'voucher_id',
            'voucher_nominal',
            'discount_id',
            'date',
            'details',
        ]);

        $payload = $this->renamePayload($payload);
        $details = $payload['details'] ?? [];
        unset($payload['details']);

        $sales = $this->sales->create($payload);
        if (!$sales['status']) {
            return response()->failed($sales['error']);
        }
        foreach ($details as $detail) {
            $detail['m_product_id'] = $detail['product_id'] ?? null;
            $detail['m_product_detail_id'] = $detail['product_detail_id'] ?? null;
            unset($detail['product_id'], $detail['product_detail_id']);
            $sales['data']->detail()->create($detail);
        }

        return response()->success(new SalesResource($sales['data']), 'Transaksi berhasil ditambahkan');
    }

    public function renamePayload($payload)
    {
        $payload['m_customer_id'] = $payload['customer_id'] ?? null;
        $payload['m_voucher_id'] = $payload['voucher_id'] ?? null;
        $payload['m_discount_id'] = $payload['discount_id'] ?? null;
        unset($payload['customer_id']);
        unset($payload['voucher_id']);
        unset($payload['discount_id']);
        return $payload;
    }
    public function show($id)
    {
        $sales = $this->sales->getById($id);

        if (!$sales['status']) {
            return response()->failed(['Data sales tidak ditemukan'], 404);
        }

        return response()->success(new SalesResource($sales['data']));
    }
    private function checkIfCustomerHasDiscount($customer, $discountId)
    {
        $customerDiscount = $customer->discounts->where('id', $discountId)->first();

        return $customerDiscount ? true : false;
    }
    public function getTransaction(Request $request)
    {
        $filter = [
            'm_customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
            'm_product_id' => isset($request->product_id) ? explode(',', $request->product_id) : [],
        ];

        // dd($filter);

        $discounts = $this->promo->getAll(['status' => 'diskon'], 100, '');
        $vouchers = $this->promo->getAll(['status' => 'voucher'], 100, '');

        $customers = $this->customers->getAll($filter);

        $transformedData = [];
        foreach ($customers['data'] as $customer) {
            $customerDiscounts = [];
            $customerVouchers = [];

            $customer = CustomerModel::with('discounts', 'vouchers')->find($customer->id);

            foreach ($discounts['data'] as $discount) {
                $isAvailable = $this->checkIfCustomerHasDiscount($customer, $discount->id);

                if (!$isAvailable) {
                    $existingDiscount = DiscountModel::where('m_customer_id', $customer->id)
                        ->where('m_promo_id', $discount->id)
                        ->first();

                    if ($existingDiscount && $existingDiscount->is_available) {
                        $isAvailable = true;
                    }
                }

                $customerDiscounts[] = [
                    'promo_id' => $discount->id,
                    'discount_name' => $discount->name,
                    'is_available' => $isAvailable ? '1' : '0',
                    'nominal_diskon' => $discount->nominal_percentage
                ];
            }

            foreach ($vouchers['data'] as $voucher) {
                $voucherDetails = VoucherModel::select('total_voucher', 'nominal_rupiah')
                    ->where('m_customer_id', $customer->id)
                    ->where('m_promo_id', $voucher->id)
                    ->first();

                $customerVouchers[] = [
                    'promo_id' => $voucher->id,
                    'voucher_name' => $voucher->name,
                    'total_voucher' => $voucherDetails->total_voucher ?? 0,
                    'nominal_rupiah' => $voucherDetails->nominal_rupiah ?? 0,
                ];
            }

            $transformedData[] = [
                'customer' => [
                    'name' => $customer->name,
                    'customer_id' => $customer->id,
                    'email' => $customer->email,
                    'date_of_birth' => $customer->date_of_birth,
                    'photo_url' => !empty($customer->photo) ? Storage::disk('public')->url($customer->photo) : Storage::disk('public')->url('../assets/img/no-image.png'),
                    'phone_number' => $customer->phone_number,
                    'is_verified' => $customer->is_verified,
                ],
                'discount' => $customerDiscounts,
                'voucher' => $customerVouchers
            ];

            $data = [
                'list' => $transformedData,
                'total' => $this->countIsAvailable($request)
            ];
        }

        return response()->success($data);
    }

    public function countIsAvailable(Request $request)
    {
        $filter = [
            'm_customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
        ];

        $discounts = $this->promo->getAll(['status' => 'diskon'], 100, '');

        $customers = $this->customers->getAll($filter);

        $countedData = [];
        foreach ($discounts['data'] as $discount) {
            $count = 0;

            foreach ($customers['data'] as $customer) {
                $customer = CustomerModel::with('discounts')->find($customer->id);

                $isAvailable = $this->checkIfCustomerHasDiscount($customer, $discount->id);

                if (!$isAvailable) {
                    $existingDiscount = DiscountModel::where('m_customer_id', $customer->id)
                        ->where('m_promo_id', $discount->id)
                        ->first();

                    if ($existingDiscount && $existingDiscount->is_available) {
                        $isAvailable = true;
                    }
                }

                if ($isAvailable) {
                    $count++;
                }
            }

            $countedData[] = [
                'promo_id' => $discount->id,
                'total_checked' => $count,
            ];
        }

        // return response()->success($countedData);
        return $countedData;
    }
    public function storeTransaction(SalesRequest $request)
    {

        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['customer_id', 'voucher_id', 'voucher_nominal', 'discount_id', 'date', 'details']);

        if (isset($payload['customer_id'])) {
            $payload['m_customer_id'] = $payload['customer_id'];
        }

        if (isset($payload['voucher_id'])) {
            $payload['m_voucher_id'] = $payload['voucher_id'];
        }
        if (isset($payload['discount_id'])) {
            $payload['m_discount_id'] = $payload['discount_id'];
        }

        $details = $payload['details'] ?? [];
        unset($payload['details']);

        // if (isset($payload['details'])) {
        //     foreach ($payload['details'] as $detail) {
        //         if (isset($detail['product_id'])) {
        //             $detail['m_product_id'] = $detail['product_id'];
        //             unset($detail['product_id']);
        //         }

        //         if (isset($payload['product_detail_id'])) {
        //             $detail['m_product_detail_id'] = $detail['product_detail_id'];
        //             unset($detail['product_detail_id']);
        //         }
        //     }
        // }

        $now = Carbon::now('Asia/Jakarta');
        $date = $now->format('Y-m-d H:i:s');
        $payload['date'] = $date;
        // dd($payload);

        $sales = $this->sales->create($payload);

        foreach ($details as $detail) {
            $detail['m_product_id'] = $detail['product_id'] ?? null;
            $detail['m_product_detail_id'] = $detail['product_detail_id'] ?? null;
            unset($detail['product_id'], $detail['product_detail_id']);
            $sales['data']->details()->create($detail);

        }

        if (!$sales['status']) {
            return response()->failed($sales['error']);
        }

        return response()->success(new SalesResource($sales['data']), "Transaksi berhasil ditambahkan");
    }

}
