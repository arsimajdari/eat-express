<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_id',
        'number',
        'firstname',
        'lastname',
        'phone',
        'city',
        'zip',
        'country',
        'address',
    ];


    /* Relations */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function generateUniqueCode()
    {
        do {
            $code = Str::lower(Str::random(10));

            $existing = ShippingAddress::where('number', $code)->first();
        } while (!is_null($existing));

        return $code;
    }
}
