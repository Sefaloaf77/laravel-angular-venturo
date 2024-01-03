<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Promo\DiscountHelper;
use App\Http\Requests\Promo\DiscountRequest;
use App\Http\Resources\Promo\DiscountResource;
use App\Http\Resources\Promo\DiscountCollection;

class DiscountController extends Controller
{
    private $discount;
    public function __construct()
    {
        $this->discount = new DiscountHelper();
    }

    public function index(Request $request)
    {
        $filter = [
            'm_customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
        ];
        $discounts = $this->discount->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new DiscountCollection($discounts['data']));
    }

    public function show($id)
    {
        $discount = $this->discount->getById($id);

        if (!($discount['status'])) {
            return response()->failed(['Data discount tidak ditemukan'], 404);
        }

        return response()->success(new DiscountResource($discount['data']));
    }


    public function store(DiscountRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['customer_id',
            'promo_id',
            'tahsin',
            'competency_matrix',
            'late_under_3',
            'full_absensi']);
        $payload = $this->renamePayload($payload);
        $discount = $this->discount->create($payload);

        if (!$discount['status']) {
            return response()->failed($discount['error']);
        }

        return response()->success(new DiscountResource($discount['data']), 'discount berhasil ditambahkan');
    }

    public function update(DiscountRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['id', 'customer_id',
            'promo_id',
            'tahsin',
            'competency_matrix',
            'late_under_3',
            'full_absensi']);
        $payload = $this->renamePayload($payload);
        $discount = $this->discount->update($payload, $payload['id'] ?? 0);

        if (!$discount['status']) {
            return response()->failed($discount['error']);
        }

        return response()->success(new DiscountResource($discount['data']), 'discount berhasil diubah');
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
}
