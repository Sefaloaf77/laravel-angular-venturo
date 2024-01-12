<?php
namespace App\Helpers\Report;

use DateTime;
use Throwable;
use App\Helpers\Venturo;
use App\Models\SalesDetailModel;

class TotalSalesHelper extends Venturo
{
    private $sales;
    public function __construct()
    {
        $this->sales = new SalesDetailModel();
    }

    private function getTotalToday()
    {
        return $this->sales->getTotalSaleByPeriode(
            (string) date('Y-m-d'),
            (string) date('Y-m-d')
        );
    }

    private function getTotalYesterday()
    {
        $date = new DateTime();
        $date->modify('-1 day');

        return $this->sales->getTotalSaleByPeriode(
            (string) $date->format('Y-m-d'),
            (string) $date->format('Y-m-d')
        );
    }

    private function getTotalThisMonth()
    {
        $startDate = new DateTime();
        $start = $startDate->modify('first day of this month')
            ->format('Y-m-d');

        $endDate = new DateTime();
        $end = $endDate->modify('last day of this month')
            ->format('Y-m-d');

        return $this->sales->getTotalSaleByPeriode((string) $start, (string) $end);
    }

    private function getTotalLastMonth()
    {
        $startDate = new DateTime();
        $start = $startDate->modify('first day of last month')
            ->format('Y-m-d');

        $endDate = new DateTime();
        $end = $endDate->modify('last day of last month')
            ->format('Y-m-d');

        return $this->sales->getTotalSaleByPeriode((string) $start, (string) $end);
    }

    public function getTotalInPeriode()
    {
        return [
            'status' => true,
            'data' => [
                'today' => $this->getTotalToday(),
                'yesterday' => $this->getTotalYesterday(),
                'this_month' => $this->getTotalThisMonth(),
                'last_month' => $this->getTotalLastMonth(),
            ]
        ];
    }

    public function getTotalPerYear()
    {
        $years = $this->sales->getListYear();
        sort($years);

        $diagram = [];
        foreach ($years as $year) {
            $total = $this->sales->getTotalPerYears($year);
            $diagram['label'][] = (string) $year;
            $diagram['data'][] = $total;
        }

        return [
            'status' => true,
            'data' => $diagram ?? []
        ];
    }

    public function getTotalPerMonth(){

        $months = $this->sales->getListMonth();
        sort($months);

        $diagram = [];
        foreach ($months as $month) {
            $total = $this->sales->getTotalPerMonths($month);
            $diagram['label'][] = (string) $month;
            $diagram['data'][] = $total;
        }

        return [
            'status' => true,
            'data' => $diagram ?? []
        ];

    }

    // public function getTotalPerMonth($startDate = null, $endDate = null)
    // {
    //     $diagram = [];
    //     $totalPerMonths = $this->sales->getTotalPerMonths($startDate, $endDate);

    //     foreach ($totalPerMonths as $result) {
    //         $diagram['label'][] = $result['month'];
    //         $diagram['data'][] = $result['total_sales'];
    //     }

    //     // dd($diagram);

    //     return [
    //         'status' => true,
    //         'data' => $diagram ?? []
    //     ];

    // }
}