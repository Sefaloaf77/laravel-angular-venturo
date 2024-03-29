<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use RecordSignature;

    public $timestamps = true;
    protected $fillable=[
        'name',
        'status',
        'expired_in_day',
        'nominal_percentage',
        'nominal_rupiah',
        'term_conditions',
        'photo'
    ];
    public function discounts()
    {
        return $this->hasMany(DiscountModel::class, 'm_promo_id', 'id');
    }

    // public function vouchers()
    // {
    //     return $this->hasMany(VoucherModel::class, 'm_promo_id');
    // }

    public function vouchers()
    {
        return $this->belongsTo(VoucherModel::class, 'm_promo_id', 'id'); // Adjust the foreign key name if necessary
    }
    protected $table = 'm_promo';

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
        $user = $this->query();

        if (!empty($filter['name'])) {
            $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if ($filter['status'] != '') {
            $user->where('status', '=', $filter['status']);
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
}
