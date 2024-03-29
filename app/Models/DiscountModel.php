<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DiscountModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use RecordSignature;

    protected $table = 'm_discount';
    public $timestamps = true;

    protected $fillable = [
        'm_customer_id',
        'm_promo_id',
        'is_available',
    ];


    //relasi ke tabel m_customer
    public function customer()
    {
        return $this->hasOne(CustomerModel::class, 'id', 'm_customer_id');
    }

    //relasi ke tabel m_promo
    // public function promo()
    // {
    //     return $this->hasOne(PromoModel::class, 'id', 'm_promo_id');
    // }
    public function promo()
    {
        return $this->belongsTo(PromoModel::class, 'm_promo_id', 'id');
    }

    public function sale()
    {
        return $this->hasMany(SalesModel::class, 'm_discount_id');
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
        // dd($filter);
        // if (!empty($filter['m_customer_id']) && is_array($filter['m_customer_id'])) {
        //     $discount->whereIn('m_customer_id', $filter['m_customer_id']);
        // }
        if (!empty($filter['m_customer_id']) && is_array($filter['m_customer_id'])) {
            $discount->whereIn('m_customer_id', $filter['m_customer_id']);
        }

        // if ($filter['status'] != '') {
        //     $discount->where('status', '=', $filter['status']);
        // }

        $sort = $sort ?: 'id DESC';
        $discount->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $discount->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id)
    {
        return $this->find($id);
    }

    public function getPromoByDiscount(){
        $promo = $this->query()->with(['promo']);

        $promo->where('status','=','diskon');
    }

    public function getByCustomerId(string $customerId)
    {
        return $this->with(['customer:id,name', 'promo:id,name,nominal_percentage'])
            ->where('m_customer_id', $customerId)
            ->where('is_available', 1)
            ->get();
    }
}
