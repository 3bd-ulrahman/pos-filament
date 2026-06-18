<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

#[UseFactory(ProductFactory::class)]
class Product extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'purchase_price',
        'sale_price',
        'stock',
    ];

    protected $translatable = ['name'];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
