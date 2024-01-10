<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $timestamps = true;
    protected $fillable = [
        'm_customer_id',
        'm_voucher_id',
        'voucher_nominal',
        'm_discount_id',
        'date'
    ];

    protected $table = 't_sales';

    //relasi ke tabel m_customer
    public function customer()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'm_customer_id');
    }

    //relasi ke tabel t_sales_detail
    public function details()
    {
        return $this->hasMany(SalesDetailModel::class, 't_sales_id', 'id');
    }

    public function voucher()
    {
        return $this->hasOne(VoucherModel::class, 'id', 'm_voucher_id');
    }

    public function discount()
    {
        return $this->hasOne(DiscountModel::class, 'id', 'm_discount_id');
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();

        // if (!empty($filter['name'])) {
        //     $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        // }

        if (!empty($filter['m_customer_id'])) {
            $user->where('m_customer_id', '=', $filter['m_customer_id']);
        }

        $sort = $sort ?: 'id DESC';
        $user->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $user->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getSalesPromo($startDate, $endDate, $customer = [], $promo = [])
    {
        $sales = $this->query()->with(['voucher', 'customer', 'voucher.promo']);

        if (!empty($startDate) && !empty($endDate)) {
            $sales->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }

        if (!empty($customer)) {
            $sales->whereIn('m_customer_id', $customer);
        }

        if (!empty($promo)) {
            $sales->where(function ($query) use ($promo) {
                $query->whereIn('m_voucher_id', $promo)
                    ->orWhereIn('m_discount_id', $promo);
            });
        }

        $sales->whereNotNull('m_voucher_id');

        return $sales->orderByDesc('date')->get();
    }

    public function getSalesTransaction(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $sale = $this->query()->with(['details', 'voucher', 'discount', 'customer']);

        // dd($filter['m_product_id']);
        if (!empty($filter['m_product_id']) && is_array($filter['m_product_id'])) {
            $sale->with([
                'details' => function ($query) use ($filter) {
                    $query->whereIn('m_product_id', $filter['m_product_id']);
                },
                'details.product'
            ])->has('details');
        }

        if (!empty($filter['m_customer_id']) && is_array($filter['m_customer_id'])) {
            $customerId = $filter['m_customer_id'];
            $sale->whereIn('m_customer_id', $customerId);
        }

        if (!empty($filter['start_date']) && !empty($filter['end_date'])) {
            $startDate = $filter['start_date'];
            $endDate = $filter['end_date'];
            $sale->whereRaw('date >= "' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }

        $sort = $sort ?: 'id DESC';
        $sale->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $sale->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getSalesByCategory($startDate, $endDate, $category = '')
    {
        $sales = $this->query()->with([
            'details.product' => function ($query) use ($category) {
                if (!empty($category)) {
                    $query->where('m_product_category_id', $category);
                }
            },
            'details',
            'details.product.category'
        ]);

        if (!empty($startDate) && !empty($endDate)) {
            $sales->whereRaw('date >="' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }

        return $sales->orderByDesc('date')->limit(2)->get();
    }

    public function getSalesByCustomer($startDate, $endDate, $customerId = '')
    {
        $sales = $this->query()->with([
            'details.product',
            'discount',
            'customer',
            'voucher.promo'
        ]);
        if (!empty($startDate) && !empty($endDate)) {
            $sales->whereRaw('date >="' . $startDate . ' 00:00:01" and date <= "' . $endDate . ' 23:59:59"');
        }
        if (!empty($customerId)) {
            $sales->where('m_customer_id', $customerId);
        }
        return $sales->orderByDesc('date')->get();
    }

    public function getByCustomerId($date, int $id)
    {
        $sale = $this->query()->with(['details', 'discount', 'customer', 'discount.promo']);

        if (!empty($date)) {
            $sale->whereRaw('date >= "' . $date . ' 00:00:01" and date <= "' . $date . ' 23:59:59"');
        }

        return $sale->where('m_customer_id', $id)->get();
    }

}
