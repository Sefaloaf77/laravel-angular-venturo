<?php

namespace App\Models;

use App\Repository\CrudInterface;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use RecordSignature;

    protected $table = 'm_customer';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'date_of_birth',
        'photo',
        'is_verified'
    ];
    public function discounts()
    {
        return $this->hasMany(DiscountModel::class, 'm_customer_id', 'id');
    }

    public function vouchers()
    {
        return $this->hasMany(VoucherModel::class, 'm_customer_id', 'id'); // Adjust the foreign key name if necessary
    }
    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();

        if (!empty($filter['name'])) {
            $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }
        if (!empty($filter['m_customer_id']) && is_array($filter['m_customer_id'])) {
            $user = $user->whereIn('id', $filter['m_customer_id']);
        }
        if (!empty($filter['email'])) {
            $user->where('email', 'LIKE', '%' . $filter['email'] . '%');
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
