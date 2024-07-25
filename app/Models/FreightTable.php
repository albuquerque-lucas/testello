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
}
