<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    ];

    protected $casts = [
        'price' => 'float',
        'discount' => 'float',
        'tax' => 'float',
        'available' => 'boolean',
    ];


    public function subcategories()
    {
        return $this->belongsToMany(Subcategory::class, 'product_subcategory');
    }


    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
