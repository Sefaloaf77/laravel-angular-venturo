<?php

namespace App\Models;

use App\Models\SalesDetailModel;
use App\Models\ProductDetailModel;
use App\Http\Traits\RecordSignature;
use App\Models\ProductCategoryModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use RecordSignature;

    public $timestamps = true;
    protected $fillable = [
        'm_product_category_id',
        'name',
        'price',
        'description',
        'photo',
        'is_available'
    ];
    protected $table = 'm_product';

    /**
     * Relasi ke ProductCategory / tabel m_product_category
     *
     * @return void
     */

    public function salesDetails(): BelongsTo
    {
        return $this->belongsTo(SalesDetailModel::class, 'm_product_id', 'id');
    }
    public function category(): HasOne
    {
        return $this->hasOne(ProductCategoryModel::class, 'id', 'm_product_category_id');
    }
    /**
     * Relasi ke ProductDetail / tabel m_product_detail
     *
     * @return void
     */
    public function details(): HasMany
    {
        return $this->hasMany(ProductDetailModel::class, 'm_product_id', 'id');
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
        $user = $this->query();
        if (!empty($filter['name'])) {
            $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        if (!empty($filter['m_product_category_id'])) {
            $user->where('m_product_category_id', '=', $filter['m_product_category_id']);
        }

        if ($filter['is_available'] != '') {
            $user->where('is_available', '=', $filter['is_available']);
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
