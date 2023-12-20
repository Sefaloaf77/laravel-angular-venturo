<?php
namespace App\Models;

use App\Http\Traits\Uuid;
use App\Repository\CrudInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserModel extends Model implements CrudInterface
{
    use HasFactory;
    use SoftDeletes; // Use SoftDeletes library
    use Uuid;

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;
    protected $attributes = [
        'user_roles_id' => 1, // memberi nilai default = 1 pada kolom user_roles_id
    ];

    /**
     * Menentukan kolom apa saja yang bisa dimanipulasi oleh UserModel
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'user_roles_id',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 'user_auth';

    public function drop(string $id)
    {
        return $this->find($id)->delete();
    }

    public function edit(array $payload, string $id)
    {
        return $this->find($id)->update($payload);
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = '')
    {
        $user = $this->query();

        if (!empty($filter['name'])) {
            $user->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        if (!empty($filter['email'])) {
            $user->where('email', 'LIKE', '%' . $filter['email'] . '%');
        }

        $sort = $sort ?: 'id DESC';
        $user->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $user->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(string $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }
}
