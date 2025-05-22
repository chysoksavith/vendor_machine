<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // relational
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }
    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = $category->slug ?? str($category->name)->slug();
        });

        static::updating(function ($category) {
            $category->slug = str($category->name)->slug();
        });
    }
}
