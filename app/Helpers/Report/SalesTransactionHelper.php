<?php
namespace App\Helpers\Report;

use Throwable;
use App\Helpers\Venturo;
use App\Models\SalesModel;

class SalesTransactionHelper extends Venturo
{
    private $sales;
    public function __construct()
    {
        $this->sales = new SalesModel();
    }


    public function get(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $sales = $this->sales->getSalesTransaction($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $sales
        ];
    }
}