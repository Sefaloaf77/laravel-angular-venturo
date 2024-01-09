<?php
namespace App\Helpers\Sales;

use Throwable;
use App\Helpers\Venturo;
use App\Models\SalesModel;
use App\Models\SalesDetailModel;

class SalesHelper extends Venturo
{
    private $sales;
    private $salesDetail;

    public function __construct()
    {
        $this->sales = new SalesModel();
        $this->salesDetail = new SalesDetailModel();
    }

    public function create(array $payload): array
    {
        try {

            $this->beginTransaction();

            $sales = $this->sales->store($payload);

            $this->insertUpdateDetail($payload['details'] ?? [], $sales->id);

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $sales
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function delete(int $salesId)
    {
        try {
            $this->beginTransaction();

            $this->sales->drop($salesId);

            $this->salesDetail->dropBySalesId($salesId);

            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $salesId
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $categories = $this->sales->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $categories
        ];
    }


    public function getById(int $id): array
    {
        $sales = $this->sales->getById($id);
        if (empty($sales)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $sales
        ];
    }

    public function update(array $payload, $id): array
    {
        try {

            $this->beginTransaction();

            $this->sales->edit($payload, $id);

            $this->insertUpdateDetail($payload['details'] ?? [], $id);
            $this->deleteDetail($payload['details_deleted'] ?? []);

            $sales = $this->getById($id);
            $this->commitTransaction();

            return [
                'status' => true,
                'data' => $sales['data']
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    private function insertUpdateDetail(array $details, int $salesId)
    {
        if (empty($details)) {
            return false;
        }

        foreach ($details as $val) {
            // Insert
            if (isset($val['is_added']) && $val['is_added']) {
                $val['t_sales_id'] = $salesId;
                $this->salesDetail->store($val);
            }

            // Update
            if (isset($val['is_updated']) && $val['is_updated']) {
                $this->salesDetail->edit($val, $val['id']);
            }
        }
    }

    private function deleteDetail(array $details)
    {
        if (empty($details)) {
            return false;
        }

        foreach ($details as $val) {
            $this->salesDetail->drop($val['id']);
        }
    }

}