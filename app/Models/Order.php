<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['gross_amount', 'tax_amount', 'net_amount'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}