<?php
namespace App\Helpers\Promo;

use Throwable;
use App\Helpers\Venturo;
use App\Models\VoucherModel;

class VoucherHelper extends Venturo
{
    private $voucher;

    public function __construct()
    {
        $this->voucher = new VoucherModel();
    }

    public function create(array $payload): array
    {
        try {
            $voucher = $this->voucher->store($payload);

            return [
                'status' => true,
                'data' => $voucher
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function delete(int $id): bool
    {
        try {
            $this->voucher->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $vouchers = $this->voucher->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $vouchers
        ];
    }

    public function getById(int $id): array
    {
        $voucher = $this->voucher->getById($id);
        if (empty($voucher)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $voucher
        ];
    }

    public function update(array $payload, int $id): array
    {
        try {
            $this->voucher->edit($payload, $id);

            $voucher = $this->getById($id);

            return [
                'status' => true,
                'data' => $voucher['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }
}