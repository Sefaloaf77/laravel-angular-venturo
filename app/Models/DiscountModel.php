<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'm_discount';
    public $timestamps = true;

    protected $fillable = [
        'm_customer_id',
        'm_promo_id',
        'tahsin',
        'competency_matrix',
        'late_under_3',
        'full_absensi'
    ];


    //relasi ke tabel m_customer
    public function customer()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'm_customer_id');
    }

    //relasi ke tabel m_promo
    public function promo()
    {
        return $this->hasOne(PromoModel::class, 'id', 'm_promo_id');
    }


    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $discount = $this->query();

        if (!empty($filter['m_customer_id']) && is_array($filter['m_customer_id'])) {
            $discount->whereIn('m_customer_id', $filter['m_customer_id']);
        }

        $sort = $sort ?: 'id DESC';
        $discount->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $discount->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id)
    {
        return $this->find($id);
    }
}
