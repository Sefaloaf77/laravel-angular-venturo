<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Sales\SalesHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Sales\SalesCollection;
use App\Http\Resources\Sales\SalesResource;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    private $sales;

    public function __construct()
    {
        $this->sales = new SalesHelper();
    }

    public function index(Request $request)
    {
        $filter = [
            'm_customer_id' => $request->m_customer_id ?? '',
            'm_product_id' => $request->m_product_id ?? '',
        ];

        $sales = $this->sales->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new SalesCollection($sales['data']));
    }

    public function show($id)
    {
        $sales = $this->sales->getById($id);

        if (!$sales['status']) {
            return response()->failed(['Data sales tidak ditemukan'], 404);
        }

        return response()->success(new SalesResource($sales['data']));
    }
}
