<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreightTable extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'from_postcode',
        'to_postcode',
        'from_weight',
        'to_weight',
        'cost',
        'branch_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
