<?php
namespace App\Helpers\Report;

use DateTime;
use Throwable;
use DatePeriod;
use DateInterval;
use App\Helpers\Venturo;
use App\Models\SalesModel;

class SalesCategoryHelper extends Venturo
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
    public function get($startDate, $endDate, $categoryId = '')
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;

        $sales = $this->sales->getSalesByCategory($startDate, $endDate, $categoryId);

        return [
            'status' => true,
            // 'data' => $this->reformatReport($sales),
            'data' => $this->reformatReport($sales, $startDate, $endDate),
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
        $salesDetail = [];

        foreach ($list as $sales) {
            foreach ($sales['details'] as $detail) {
                if (empty($detail['product'])) {
                    continue;
                }

                $date = date('Y-m-d', strtotime($sales['date']));
                $categoryId = $detail['product']['m_product_category_id'];
                $categoryName = $detail['product']['category']['name'];
                $productName = $detail['product']['name'];
                $productId = $detail['product']['id'];
                $totalSales = $detail['price'] * $detail['total_item'];
                $listTransactions = $salesDetail[$categoryId]['products'][$productId]['transactions'] ?? $periods;
                $subTotal = $salesDetail[$categoryId]['products'][$productId]['transactions'][$date]['total_sales'] ?? 0;
                $totalPerProduct = $salesDetail[$categoryId]['products'][$productId]['transactions_total'] ?? 0;
                $totalPerCategory = $salesDetail[$categoryId]['category_total'] ?? 0;

                $salesDetail[$categoryId] = [
                    'category_id' => $categoryId,
                    'category_name' => $categoryName,
                    'category_total' => $totalPerCategory + $totalSales,
                    'products' => $salesDetail[$categoryId]['products'] ?? [],
                ];

                $salesDetail[$categoryId]['products'][$productId] = [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'transactions' => $listTransactions,
                    'transactions_total' => $totalPerProduct + $totalSales
                ];

                $salesDetail[$categoryId]['products'][$productId]['transactions'][$date] = [
                    'date_transaction' => $date,
                    'total_sales' => $subTotal + $totalSales
                ];

                $this->totalPerDate[$date] = ($this->totalPerDate[$date] ?? 0) + $totalSales;
                $this->total = ($this->total ?? 0) + $totalSales;
            }
        }
        return $this->convertNumericKey($salesDetail);
    }

    private function convertNumericKey($salesDetail)
    {
        $indexSales = 0;

        foreach ($salesDetail as $sales) {
            $list[$indexSales] = [
                'category_id' => $sales['category_id'],
                'category_name' => $sales['category_name'],
                'category_total' => $sales['category_total'],
            ];

            $indexProducts = 0;
            foreach ($sales['products'] as $product) {
                $list[$indexSales]['products'][$indexProducts] = [
                    'product_id' => $product['product_id'],
                    'product_name' => $product['product_name'],
                    'transactions' => array_values($product['transactions']),
                    'transactions_total' => $product['transactions_total']
                ];
                $indexProducts++;
            }
            $indexSales++;
        }
        unset($salesDetail);
        return $list ?? [];
    }


}