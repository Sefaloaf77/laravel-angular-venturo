<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Report\TotalSalesHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesSummaryController extends Controller
{
    private $sales;
    public function __construct()
    {
        $this->sales = new TotalSalesHelper();
    }

    public function getDiagramPerYear()
    {
        $sales = $this->sales->getTotalPerYear();

        return response()->success($sales['data']);
    }

    public function getDiagramPerMonth()
    {
        $sales = $this->sales->getTotalPerMonth();
        return response()->success($sales['data']);
    }

    // public function getDiagramPerMonth(Request $request)
    // {
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');
    //     $sales = $this->sales->getTotalPerMonth($startDate, $endDate);

    //     return response()->success($sales['data']);
    // }

    public function getTotalSummary()
    {
        $sales = $this->sales->getTotalInPeriode();
        return response()->success($sales['data']);
    }
}
