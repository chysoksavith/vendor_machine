<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    public function all(): collection
    {
        return Category::with('parent')->orderBy('order')->get();
    }

    public function getRootCategories()
    {
        return Category::root()->active()->with('children')->orderBy('order')->get();
    }

    public function find($id): ?Category
    {
        return Category::with(['parent', 'children'])->findOrFail($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }


    public function update(Category $category, array $data): bool
    {
        return $category->update($data);
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }
    public function getParentOptions($excludeId = null)
    {
        $query = Category::query();

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->pluck('name', 'id');
    }
}
