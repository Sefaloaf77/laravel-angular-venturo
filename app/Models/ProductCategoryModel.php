<?php

namespace App\Models;

use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductCategoryModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    // use RecordSignature;
    
    public $timestamps = true;
    protected $fillable = [
        'name','index'
    ];
    protected $table= 'm_product_category';

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
