<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use App\Helpers\Role\RoleHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\CreateRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Http\Resources\Role\RoleCollection;

class RoleController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->role = new RoleHelper();
    }

    /**
     * Mengambil list role
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function index(Request $request)
    {
        $filter = [
            'nama' => $request->nama ?? '',
            'email' => $request->email ?? ''
        ];
        $roles = $this->role->getAll($filter, 0, $request->sort ?? '');

        return response()->success(new RoleCollection($roles['data']));
    }

    /**
     * Membuat data role baru & disimpan ke tabel user_roles
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function store(CreateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'access']);
        $role = $this->role->create($payload);

        if (!$role['status']) {
            return response()->failed($role['error']);
        }

        return response()->success(new RoleResource($role['data']),"Role berhasil ditambahkan");
    }

    /**
     * Menampilkan role secara spesifik dari tabel user_roles
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function show($id)
    {
        $role = $this->role->getById($id);

        if (!($role['status'])) {
            return response()->failed(['Data role tidak ditemukan'], 404);
        }

        return response()->success(new RoleResource($role['data']));
    }

    /**
     * Mengubah data role di tabel user_roles
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function update(UpdateRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'access', 'id',]);
        $role = $this->role->update($payload, $payload['id']);

        if (!$role['status']) {
            return response()->failed($role['error']);
        }

        return response()->success(new RoleResource($role['data']),"Role berhasil diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = $this->role->delete($id);

        if (!$role) {
            return response()->failed(['Mohon maaf data role tidak ditemukan']);
        }

        return response()->success($role, "Role Berhasil dihapus");
    }
}
