<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(private CategoryRepository $categoryRepository) {}

    public function index(): View
    {
        $categories = $this->categoryRepository->all();
        return view('admin.categories.index', compact('categories'));
    }
    public function create(): View
    {
        $parents = $this->categoryRepository->getParentOptions();

        return view('admin.categories.create', compact('parents'));
    }
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
  
        $this->categoryRepository->create($request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }
}
