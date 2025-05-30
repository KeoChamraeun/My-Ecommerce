<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Gloudemans\Shoppingcart\CanBeBought;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements Buyable
{
    use SoftDeletes;
    use CanBeBought;
    use HasAdvancedFilter;
    use HasFactory;

    public const StatusInActive = 0;
    public const StatusActive = 1;

    public $orderable = [
        'id',
        'name',
        'description',
        'price',
        'code',
        'category_id',
        'brand_id',
        'status',
    ];

    public $filterable = [
        'id',
        'name',
        'description',
        'price',
        'code',
        'category_id',
        'brand_id',
        'status',
    ];

    protected $fillable = [
        'name',
        'description',
        'price',
        'old_price',
        'slug',
        'code',
        'image',
        'gallery',
        'embeded_video',
        'category_id',
        'subcategories',
        'options',
        'brand_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'status',
        'featured',
        'hot',
        'best',
        'top',
        'latest',
        'big',
        'trending',
        'sale',
        'is_discount',
        'discount_date',
    ];

    protected $casts = [
        'subcategories' => 'array',
        'options' => 'array',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getDiscountAttribute()
    {
        if ($this->old_price) {
            return round(($this->old_price - $this->price) / $this->old_price * 100);
        }
        return null;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class);
    }

    public function scopeHighestPrice($query)
    {
        return $query->orderBy('price', 'desc')->first();
    }

    public function scopeLowestPrice($query)
    {
        return $query->orderBy('price', 'asc')->first();
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')->withPivot('qty', 'price', 'tax', 'total');
    }
}
