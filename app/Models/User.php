<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function isAdmin()
    {
        if ($this->roles()->where('name', 'admin')->exists()) return true;
    }

    public function getNameAttribute()
    {
        return $this->attributes['firstname'] . ' ' . $this->attributes['lastname'];
    }


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class)->with('product')->latest();
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function shippingAddress(): HasMany
    {
        return $this->hasMany(ShippingAddress::class)->whereNull('order_id')->latest();
    }


    public function total()
    {
        $total = 0;

        foreach ($this->items as $item) $total += $item->price * $item->quantity;

        return $total;
    }

    public function cartSize()
    {
        return $this->items()->sum('quantity');
    }

    public function hasShipping()
    {
        return $this->shippingAddress()->exists();
    }
}
