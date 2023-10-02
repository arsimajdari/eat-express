<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $with = ['images'];

    protected $fillable = [
        'slug',
        'name',
        'description',
        'long_description',
        'price',
        'tax',
        'discount',
        'sku',
        'available',
        'image_src',
        'category_id',
        'subcategory_id'
    ];

    protected $casts = [
        'price' => 'float',
        'discount' => 'float',
        'tax' => 'float',
        'available' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }



    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
