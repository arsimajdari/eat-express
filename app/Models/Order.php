<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $with = ['shippingAddress', 'items'];

    protected $fillable = [
        'number',
        'status',
        'user_id',
        'comment',
    ];

    protected $casts = [
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }

    public function generateUniqueCode()
    {
        do {
            $code = Str::upper(Str::random(10));

            $existing = Order::where('number', $code)->first();
        } while (!is_null($existing));

        return $code;
    }

    public function total()
    {
        $total = 0;

        foreach ($this->items as $item) $total += $item->price * $item->quantity;

        return $total;
    }
}
