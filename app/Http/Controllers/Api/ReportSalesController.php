<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Report\SalesPromoHelper;
use App\Helpers\Report\SalesTransactionHelper;
use App\Http\Resources\Report\SalesPromoCollection;
use App\Http\Resources\Report\SalesTransactionCollection;

class ReportSalesController extends Controller
{
    private $salesPromo;
    private $salesTransaction;
    public function __construct()
    {
        $this->salesPromo = new SalesPromoHelper();
        $this->salesTransaction = new SalesTransactionHelper();
    }

    public function viewSalesPromo(Request $request)
    {
        $startDate = $request->start_date ?? null;
        $endDate = $request->end_date ?? null;
        $customerId = isset($request->customer_id) ? explode(',', $request->customer_id) : [];
        $promoId = isset($request->promo_id) ? explode(',', $request->promo_id) : [];

        $sales = $this->salesPromo->get($startDate, $endDate, $customerId, $promoId);
        return response()->success(new SalesPromoCollection($sales['data']));
    }

    public function viewSalesTransaction(Request $request)
    {
        $filter = [
            'start_date' => isset($request->start_date) ? $request->start_date : '',
            'end_date' => isset($request->end_date) ? $request->end_date : '',
            'm_customer_id' => isset($request->customer_id) ? explode(',', $request->customer_id) : [],
            'm_product_id' => isset($request->product_id) ? explode(',', $request->product_id) : [],
        ];

        $sales = $this->salesTransaction->get($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->success(new SalesTransactionCollection($sales['data']));
    }
}
