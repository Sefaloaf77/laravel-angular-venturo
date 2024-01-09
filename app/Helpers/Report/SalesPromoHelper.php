<?php
namespace App\Helpers\Report;

use Throwable;
use App\Helpers\Venturo;
use App\Models\SalesModel;

class SalesPromoHelper extends Venturo
{
    private $sales;
    public function __construct()
    {
        $this->sales = new SalesModel();
    }

    public function get($startDate, $endDate, $customerId = [], $promoId = [])
    {
        $sales = $this->sales->getSalesPromo($startDate, $endDate, $customerId, $promoId);

        return [
            'status' => true,
            'data' => $sales
        ];
    }
}