<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'position',
        'meta_title',
        'meta_description'
    ];
    protected $cast = [
        'is_active' => 'boolean'
    ];


    public static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = $category->generateSlug($category->name);
        });
        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = $category->generateSlug($category->name);
            }
        });
    }
    // generate slug
    public function generateSlug($name)
    {
        $slug = Str::slug($name);
        // check if slug exist
        $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")
            ->where('id', '!=', $this->id ?? 0)
            ->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
