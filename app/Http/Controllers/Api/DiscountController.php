<?php

namespace App\Http\Controllers\Api;

use App\Models\PromoModel;
use Illuminate\Http\Request;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Helpers\Promo\PromoHelper;
use App\Http\Controllers\Controller;
use App\Helpers\Promo\DiscountHelper;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Customer\CustomerHelper;
use App\Http\Requests\Promo\DiscountRequest;
use App\Http\Resources\Promo\PromoCollection;
use App\Http\Resources\Promo\DiscountResource;
use App\Http\Resources\Promo\DiscountCollection;

class DiscountController extends Controller
{
    private $discount;
    private $customers;
    private $promo;

    public function __construct()
    {
        $this->discount = new DiscountHelper();
        $this->customers = new CustomerHelper();
        $this->promo = new PromoHelper();
    }

    public function getTableHeadings()
    {
        $tableHeadings = []; // Initial headings

        // Fetch discount names dynamically and append to tableHeadings
        $discounts = $this->promo->getAll(['status' => 'diskon'], 100, ''); // Fetch discounts
        foreach ($discounts['data'] as $discount) {
            $tableHeadings[]['name'] = $discount->name;
        }

        return response()->success($tableHeadings);
    }

    // Check if the customer has a specific discount
    private function checkIfCustomerHasDiscount($customer, $discountId)
    {
        // Check if the discount exists in the loaded discounts for the customer
        $customerDiscount = $customer->discounts->where('id', $discountId)->first();

        return $customerDiscount ? true : false;
    }

    public function index(Request $request)
    {
        $filter = [
            'm_customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
        ];
        $discounts = $this->discount->getAll($filter, $request->per_page ?? 50, $request->sort ?? '');

        return response()->success(new DiscountCollection($discounts['data']));
    }


    public function store(DiscountRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['customer_id', 'promo_id', 'is_available']);
        $payload = $this->renamePayload($payload);
        $discount = $this->discount->create($payload);

        if (!$discount['status']) {
            return response()->failed($discount['error']);
        }

        return response()->success(new DiscountResource($discount['data']), 'Diskon berhasil ditambahkan');
    }

    public function show($id)
    {
        $customer = CustomerModel::with('discounts')->find($id);

        if (!$customer) {
            return response()->failed(['Customer not found'], 404);
        }

        $discounts = $this->promo->getAll(['status' => 'diskon'], 100, '');

        $customerDiscounts = [];
        foreach ($discounts['data'] as $discount) {
            $isAvailable = $this->checkIfCustomerHasDiscount($customer, $discount->id);

            // Check if is_available already exists in the database for this customer and discount
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
                'nama_diskon' => $discount->name,
                'is_available' => $isAvailable ? '1' : '0',
            ];
        }

        $transformedData = [
            'customer' => [
                'name' => $customer->name,
                'customer_id' => $customer->id,
                'email' => $customer->email,
                'date_of_birth' => $customer->date_of_birth,
                'photo_url' => !empty($customer->photo) ? Storage::disk('public')->url($customer->photo) : Storage::disk('public')->url('../assets/img/no-image.png'),
                'phone_number' => $customer->phone_number,
                'is_verified' => $customer->is_verified,
            ],
            'diskon' => $customerDiscounts,
        ];

        return response()->success($transformedData);
    }

    public function update(DiscountRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['id', 'customer_id', 'promo_id', 'is_available']);
        $payload = $this->renamePayload($payload);
        $discount = $this->discount->update($payload, $payload['id'] ?? 0);

        if (!$discount['status']) {
            return response()->failed($discount['error']);
        }

        return response()->success(new DiscountResource($discount['data']), 'Diskon berhasil diubah');
    }


    public function destroy($id)
    {
        $discount = $this->discount->delete($id);

        if (!$discount) {
            return response()->failed(['Mohon maaf discount tidak ditemukan']);
        }

        return response()->success($discount, 'discount berhasil dihapus');
    }

    public function renamePayload($payload)
    {
        $payload['m_customer_id'] = $payload['customer_id'] ?? null;
        $payload['m_promo_id'] = $payload['promo_id'] ?? null;
        unset($payload['customer_id']);
        unset($payload['promo_id']);
        return $payload;
    }

    public function getByCust(string $customerId)
    {
        $discountModel = new DiscountModel();
        $discounts = $discountModel->getByCustomerId($customerId);

        return response()->json($discounts);
    }
}
