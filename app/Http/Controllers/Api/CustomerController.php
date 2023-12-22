<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Customer\CustomerHelper;
use App\Http\Requests\Customer\CreateRequest;
use App\Http\Requests\Customer\UpdateRequest;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\CustomerCollection;

class CustomerController extends Controller
{
    private $customer;

    public function __construct()
    {
        $this->customer = new CustomerHelper();
    }

    /**
     * Mengambil data customer dilengkapi dengan pagination
     *
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
        ];
        $customers = $this->customer->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new CustomerCollection($customers['data']));
    }

    /**
     * Membuat data user baru & disimpan ke tabel user_auth
     *
     */
    public function store(CreateRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Customer/CreateRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'email', 'phone_number', 'date_of_birth', 'photo', 'is_verified']);
        $customer = $this->customer->create($payload);

        if (!$customer['status']) {
            return response()->failed($customer['error']);
        }

        return response()->success(new CustomerResource($customer['data']), "Customer berhasil ditambahkan");
    }

    /**
     * Menampilkan customer secara spesifik dari tabel m_customer
     *
     * @param mixed $id
     */
    public function show($id)
    {
        $customer = $this->customer->getById($id);

        if (!($customer['status'])) {
            return response()->failed(['Data customer tidak ditemukan'], 404);
        }
        return response()->success(new CustomerResource($customer['data']));
    }

    /**
     * Mengubah data customer di tabel m_customer
     *
     */
    public function update(UpdateRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/Customer/UpdateRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'email', 'phone_number', 'date_of_birth', 'photo', 'is_verified']);
        $customer = $this->customer->update($payload, $payload['id'] ?? 0);

        if (!$customer['status']) {
            return response()->failed($customer['error']);
        }

        return response()->success(new CustomerResource($customer['data']), "Customer berhasil diubah");
    }

    /**
     * Delete data customer
     *
     * @param mixed $id
     */
    public function destroy($id)
    {
        $customer = $this->customer->delete($id);

        if (!$customer) {
            return response()->failed(['Mohon maaf data customer tidak ditemukan']);
        }

        return response()->success($customer, "Customer berhasil dihapus");
    }
}
