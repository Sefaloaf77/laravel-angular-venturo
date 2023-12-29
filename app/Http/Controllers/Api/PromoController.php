<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Promo\PromoHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Promo\CreateRequest;
use App\Http\Requests\Promo\UpdateRequest;
use App\Http\Resources\Promo\PromoCollection;
use App\Http\Resources\Promo\PromoResource;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    private $promo;

    public function __construct()
    {
        $this->promo = new PromoHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'status' => isset($request->status) ? $request->status : '',
        ];
        $promos = $this->promo->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new PromoCollection($promos['data']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name',
            'status',
            'expired_in_day',
            'nominal_percentage',
            'nominal_rupiah',
            'term_conditions',
            'photo']);
        $promo = $this->promo->create($payload);

        if (!$promo['status']) {
            return response()->failed($promo['error']);
        }

        return response()->success(new PromoResource($promo['data']), "Promo berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $promo = $this->promo->getById($id);

        if (!($promo['status'])) {
            return response()->failed(['Data Promo tidak ditemukan'], 404);
        }
        return response()->success(new PromoResource($promo['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['id', 'name',
            'status',
            'expired_in_day',
            'nominal_percentage',
            'nominal_rupiah',
            'term_conditions',
            'photo']);
        $promo = $this->promo->update($payload, $payload['id'] ?? 0);

        if (!$promo['status']) {
            return response()->failed($promo['error']);
        }

        return response()->success(new PromoResource($promo['data']), "Promo berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promo = $this->promo->delete($id);

        if (!$promo) {
            return response()->failed(['Mohon maaf data promo tidak ditemukan']);
        }

        return response()->success($promo, "Promo berhasil dihapus");
    }
}
