<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDetailModel extends Model
{
    use HasFactory;
    use SoftDeletes;

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
}
