<?php
namespace App\Helpers\Customer;

use App\Helpers\Venturo;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Helper untuk manajemen customer
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_customer
 *
 */
class CustomerHelper extends Venturo
{
    const CUSTOMER_PHOTO_DIRECTORY = 'foto-customer';
    private $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    /**
     * method untuk menginput data baru ke tabel m_customer
     *
     *
     * @param array $payload
     *                       $payload['name'] = string
     *                       $payload['email] = string
     *                       $payload['phone_number] = string
     *                       $payload['date_of_birth'] = date
     *                       $payload['photo'] = string
     *
     * @return array
     */
    public function create(array $payload): array
    {
        try {
            $payload = $this->uploadGetPayload($payload);
            $customer = $this->customerModel->store($payload);

            return [
                'status' => true,
                'data' => $customer
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Menghapus data customer dengan sistem "Soft Delete"
     * yaitu mengisi kolom deleted_at agar data tsb tidak
     * keselect waktu menggunakan Query
     *
     * @param integer $id id dari tabel m_customer
     *
     * @return bool
     */
    public function delete(int $id): bool
    {
        try {
            $this->customerModel->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    /**
     * Mengambil data customer dari tabel m_customer
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
        $customers = $this->customerModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $customers
        ];
    }

    /**
     * Mengambil 1 data customer dari tabel m_customer
     *
     * @param integer $id id dari tabel m_customer
     *
     * @return array
     */
    public function getById(int $id): array
    {
        $customer = $this->customerModel->getById($id);
        if (empty($customer)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $customer
        ];
    }

    /**
     * method untuk mengubah customer pada tabel m_customer
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     *
     * @param array $payload
     *                       $payload['name'] = string
     *                       $payload['email] = string
     *                       $payload['phone_number] = string
     *                       $payload['date_of_birth'] = date
     *                       $payload['photo'] = string
     *
     * @return array
     */
    public function update(array $payload, int $id): array
    {
        try {
            // if (isset($payload['password']) && !empty($payload['password'])) {
            //     $payload['password'] = Hash::make($payload['password']) ?: '';
            // } else {
            //     unset($payload['password']);
            // }
            $payload = $this->uploadGetPayload($payload);
            $this->customerModel->edit($payload, $id);

            $customer = $this->getById($id);

            return [
                'status' => true,
                'data' => $customer['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * Upload file and remove payload when photo is not exist
     *
     *
     * @param array $payload
     * @return array
     */
    private function uploadGetPayload(array $payload)
    {
        /**
         * Jika dalam payload terdapat base64 foto, maka Upload foto ke folder public/uploads/foto-user
         */
        if (!empty($payload['photo'])) {
            $fileName = $this->generateFileName($payload['photo'], 'CUSTOMER_' . date('Ymdhis'));
            $photo = $payload['photo']->storeAs(self::CUSTOMER_PHOTO_DIRECTORY, $fileName, 'public');
            $payload['photo'] = $photo;
        } else {
            unset($payload['photo']);
        }

        return $payload;
    }
}
