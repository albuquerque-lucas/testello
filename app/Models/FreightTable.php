<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FreightTable extends Model
{
    use HasFactory;
    protected $fillable = [
        'branch_id',
        'customer_id',
        'from_postcode',
        'to_postcode',
        'from_weight',
        'to_weight',
        'cost',
    ];


    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public static function search(array $params)
    {
        $query = self::query();

        foreach ($params as $key => $value) {
            if (in_array($key, (new self)->fillable) && !is_null($value)) {
                if (in_array($key, ['from_postcode', 'to_postcode'])) {
                    $query->where($key, 'like', "%$value%");
                } else {
                    $query->where($key, $value);
                }
            }
        }

        return $query->paginate(15);
    }
}
