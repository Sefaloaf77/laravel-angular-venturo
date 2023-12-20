<?php
namespace App\Helpers\Role;

use App\Helpers\Venturo;
use App\Models\RoleModel;
use Throwable;

/**
 * Helper untuk manajemen role
 * Mengambil data, menambah, mengubah, & menghapus ke tabel user_roles
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class RoleHelper extends Venturo
{
    private $roleModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }

    /**
     * method untuk menginput data baru ke tabel user_role
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     *                       $payload['name'] = string
     *                       $payload['email'] = string
     *
     * @return array
     */
    public function create(array $payload): array
    {
        try {
            $role = $this->roleModel->store($payload);

            return [
                'status' => true,
                'data' => $role
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Menghapus data role dengan sistem "Soft Delete"
     * yaitu mengisi kolom deleted_at agar data tsb tidak
     * keselect waktu menggunakan Query
     *
     * @param integer $id id dari tabel user_roles
     *
     * @return bool
     */
    public function delete(string $id): bool
    {
        try {
            $this->roleModel->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    /**
     * Mengambil data role dari tabel user_role
     *
     * @author Wahyu Agung <wahyuagung26@gmail.com>
     *
     * @param array $filter
     *                      $filter['name'] = string
     *                      $filter['email'] = string
     * @param integer $itemPerPage jumlah data yang ditampilkan, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return array
     */
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $roles = $this->roleModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $roles
        ];
    }

    /**
     * Mengambil 1 data role dari tabel user_roles
     *
     * @param integer $id id dari tabel user_roles
     *
     * @return array
     */
    public function getById(string $id): array
    {
        $role = $this->roleModel->getById($id);
        if (empty($role)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $role
        ];
    }

    /**
     * method untuk mengubah role pada tabel user_roles
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     *                       $payload['name'] = string
     *                       $payload['email'] = string
     *
     * @return array
     */
    public function update(array $payload, string $id): array
    {
        try {

            $this->roleModel->edit($payload, $id);

            $role = $this->getById($id);

            return [
                'status' => true,
                'data' => $role['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

}
