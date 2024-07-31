<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function freightTables(): HasMany
    {
        return $this->hasMany(FreightTable::class);
    }

    public static function search(array $params, string $sortOrder = 'desc')
    {
        $query = self::query();
        foreach ($params as $key => $value) {
            if (in_array($key, ['name'])) {
                $query->where($key, 'like', "%$value%");
            }
        }

        return $query->orderBy('id', $sortOrder)->paginate();
    }
}
