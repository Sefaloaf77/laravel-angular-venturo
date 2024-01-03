<?php
namespace App\Helpers\Promo;

use Throwable;
use App\Helpers\Venturo;
use App\Models\DiscountModel;

class DiscountHelper extends Venturo
{
    private $discount;

    public function __construct()
    {
        $this->discount = new DiscountModel();
    }

    public function create(array $payload): array
    {
        try {
            $discount = $this->discount->store($payload);

            return [
                'status' => true,
                'data' => $discount
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
            $this->discount->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $discounts = $this->discount->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $discounts
        ];
    }

    public function getById(int $id): array
    {
        $discount = $this->discount->getById($id);
        if (empty($discount)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $discount
        ];
    }

    public function update(array $payload, int $id): array
    {
        try {
            $this->discount->edit($payload, $id);

            $discount = $this->getById($id);

            return [
                'status' => true,
                'data' => $discount['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }
}