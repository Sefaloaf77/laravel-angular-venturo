<?php
namespace App\Helpers\Report;

use DateTime;
use Throwable;
use DatePeriod;
use DateInterval;
use App\Helpers\Venturo;
use App\Models\SalesModel;

class SalesCustomerHelper extends Venturo
{
    private $dates;
    private $endDate;
    private $sales;
    private $startDate;
    private $total;
    private $totalPerDate;
    public function __construct()
    {
        $this->sales = new SalesModel();
    }
    public function get($startDate, $endDate, $customerId = '')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $sales = $this->sales->getSalesByCustomer($startDate, $endDate, $customerId);
        // dd($sales);

        return [
            'status' => true,
            'data' => $this->reformatReport($sales),
            // 'data' => $this->reformatReport($sales, $startDate, $endDate),
            'dates' => array_values($this->dates),
            'total_per_date' => array_values($this->totalPerDate),
            'grand_total' => $this->total
        ];
    }
    private function getPeriode()
    {
        $begin = new DateTime($this->startDate);
        $end = new DateTime($this->endDate);
        $end = $end->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $date = $dt->format('Y-m-d');
            $dates[$date] = [
                'date_transaction' => $date,
                'total_sales' => 0
            ];
            $this->setDefaultTotal($date);
            $this->setSelectedDate($date);
        }
        return $dates ?? [];
    }

    private function setDefaultTotal(string $date)
    {
        $this->totalPerDate[$date] = 0;
    }

    private function setSelectedDate(string $date)
    {
        $this->dates[] = $date;
    }

    private function reformatReport($list)
    {
        $list = $list->toArray();
        $periods = $this->getPeriode();
        $salesDetails = [];
        // dd($list);
        foreach ($list as $sales) {
            foreach ($sales['details'] as $detail) {
                if (empty($sales['customer'])) {
                    continue;
                }

                $date = date('Y-m-d', strtotime($sales['date']));
                $customerId = $sales['customer']['id'];
                $customerName = $sales['customer']['name'];
                $totalSales = $detail['price'] * $detail['total_item'];
                $listTransactions = $salesDetails[$customerId]['customers']['transactions'] ?? $periods;
                $subTotal = $salesDetails[$customerId]['customers']['transactions'][$date]['total_sales'] ?? 0;
                $totalPerCustomer = $salesDetails[$customerId]['customers']['transactions_total'] ?? 0;
                $totalCustomers = $salesDetails[$customerId]['customer_total'] ?? 0;

                $salesDetails[$customerId] = [
                    'customer_id' => $customerId,
                    'customer_name' => $customerName,
                    'customer_total' => $totalCustomers + $totalSales,
                    'customers' => $salesDetails[$customerId]['customers'] ?? [],
                ];

                $salesDetails[$customerId]['customers'] = [
                    'transactions' => $listTransactions,
                    'transactions_total' => $totalPerCustomer + $totalSales
                ];


                $salesDetails[$customerId]['customers']['transactions'][$date] = [
                    'date_transaction' => $date,
                    'total_sales' => $subTotal + $totalSales
                ];

                $this->totalPerDate[$date] = ($this->totalPerDate[$date] ?? 0) + $totalSales;
                $this->total = ($this->total ?? 0) + $totalSales;
            }
        }
        return $this->convertNumericKey($salesDetails);
    }

    private function convertNumericKey($salesDetails)
    {
        $indexSales = 0;

        foreach ($salesDetails as $sales) {
            $list[$indexSales] = [
                'customer_id' => $sales['customer_id'],
                'customer_name' => $sales['customer_name'],
                'customer_total' => $sales['customer_total'],
                'transactions' => array_values($sales['customers']['transactions']),
                'transactions_total' => $sales['customers']['transactions_total']
            ];
            $indexSales++;
        }
        unset($salesDetails);
        return $list ?? [];
    }


}