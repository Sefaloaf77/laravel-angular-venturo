<?php

namespace App\Models;

use App\Models\SalesModel;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesDetailModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use RecordSignature;

    public $timestamps = true;
    protected $fillable = [
        't_sales_id',
        'm_product_id',
        'm_product_detail_id',
        'total_item',
        'price',
        'discount_nominal'
    ];
    protected $table = 't_sales_detail';

    public function sales()
    {
        return $this->belongsTo(SalesModel::class, 'id', 't_sales_id');
    }
    public function sale()
    {
        return $this->belongsTo(SalesModel::class, 't_sales_id', 'id');
    }

    public function product()
    {
        return $this->hasOne(ProductModel::class, 'id', 'm_product_id');
    }

    public function productDetail()
    {
        return $this->hasMany(ProductDetailModel::class, 'id', 'm_product_detail_id');
    }
    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }

    public function dropBySalesId(int $salesId)
    {
        return $this->where('t_sales_id', $salesId)->delete();
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();

        if (!empty($filter['type'])) {
            $user->where('type', 'LIKE', '%' . $filter['type'] . '%');
        }

        if (!empty($filter['m_product_id'])) {
            $user->where('m_product_id', 'LIKE', '%' . $filter['m_product_id'] . '%');
        }
        if (!empty($filter['t_sales_id'])) {
            $user->where('t_sales_id', 'LIKE', '%' . $filter['t_sales_id'] . '%');
        }
        $sort = $sort ?: 'id DESC';
        $user->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $user->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function getTotalSaleByPeriode(string $startDate, string $endDate): int
    {
        $total = $this->query()
            ->select(DB::raw('sum((total_item * price) - discount_nominal) as total_sales'))
            ->whereHas('sale', function ($query) use ($startDate, $endDate) {
                $query->whereRaw('date >= "' . $startDate . ' 00:00:01" 
                                     and date <= "' . $endDate . ' 23:59:59"');
            })
            ->first()
            ->toArray();

        return $total['total_sales'] ?? 0;
    }

    public function getListYear()
    {
        $sales = new SalesModel();
        $years = $sales->query()
            ->select(DB::raw('Distinct(year(date)) as year'))
            ->get()
            ->toArray();

        return array_map(function ($year) {
            return $year['year'];
        }, $years);
    }

    public function getTotalPerYears($year)
    {
        $total = $this->query()
            ->select(DB::raw('sum((total_item * price) - discount_nominal) as total_sales'))
            ->whereHas('sale', function ($query) use ($year) {
                $query->whereYear('date', '=', $year);
            })
            ->first()
            ->toArray();

        return $total['total_sales'] ?? 0;
    }

    public function getListMonth()
    {
        $sales = new SalesModel();
        $months = $sales->query()
            ->select(DB::raw('Distinct(month(date)) as month'))
            ->get()
            ->toArray();

        return array_map(function ($month) {
            // return date("F", mktime(0, 0, 0, $month['month'], 1));
            return $month['month'];
        }, $months);
    }

    public function getTotalPerMonths($month)
    {
        $total = $this->query()
            ->select(DB::raw('sum((total_item * price) - discount_nominal) as total_sales'))
            ->whereHas('sale', function ($query) use ($month) {
                $query->where(DB::raw('month(date)'), '=', $month);
                // $query->whereMonth('date', '=', $month);
            })
            ->first()
            ->toArray();

        return $total['total_sales'] ?? 0;
    }
    // public function getTotalPerMonths($startDate, $endDate)
    // {
    //     $monthlySales = [];

    //     for ($month = 1; $month < 12; $month++) {
    //         $startOfMonth = date('Y-m-01', mktime(0, 0, 0, $month, 1));
    //         $endOfMonth = date('Y-m-t', mktime(0, 0, 0, $month, 1));

    //         if (($startDate !== null && $endOfMonth < $startDate) || ($endDate !== null && $startOfMonth > $endDate)) {
    //             continue; // Skip months not within the specified range
    //         }

    //         $total = $this->query()
    //             ->select(DB::raw('sum((total_item * price) - discount_nominal) as total_sales'))
    //             ->whereHas('sale', function ($query) use ($startOfMonth, $endOfMonth) {
    //                 $query->whereBetween('date', [$startOfMonth, $endOfMonth]);
    //             })
    //             ->first()
    //             ->toArray();

    //         $monthlySales[] = [
    //             'month' => date("F", mktime(0, 0, 0, $month, 1)),
    //             'total_sales' => $total['total_sales'] ?? 0,
    //         ];
    //     }

    //     return $monthlySales;
    // }
}
