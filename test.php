
<?php
// app/Http/Controllers/Admin/CategoryController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
        $this->middleware('auth');
    }

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

    public function show(Category $category): View
    {
        return view('admin.categories.show', [
            'category' => $this->categoryRepository->find($category->id)
        ]);
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
            'parents' => $this->categoryRepository->getParentOptions($category->id)
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->categoryRepository->update($category, $request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryRepository->delete($category);
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');


            ?>



@extends('layouts.admin')

@section('content')
<div class="card bg-base-100 shadow-lg">
    <div class="card-body">
        <h2 class="card-title text-2xl font-bold mb-6">Create New Category</h2>

        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @include('admin.categories._form')

                <div class="md:col-span-2">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        Create Category
                    </button>

                    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost ml-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

<div class="form-control">
    <label class="label" for="name">
        <span class="label-text">Name *</span>
    </label>
    <input type="text" id="name" name="name"
           class="input input-bordered @error('name') input-error @enderror"
           value="{{ old('name', $category->name ?? '') }}" required>
    @error('name')
        <label class="label">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </label>
    @enderror
</div>

<div class="form-control">
    <label class="label" for="description">
        <span class="label-text">Description</span>
    </label>
    <textarea id="description" name="description"
              class="textarea textarea-bordered h-24 @error('description') textarea-error @enderror">{{ old('description', $category->description ?? '') }}</textarea>
    @error('description')
        <label class="label">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </label>
    @enderror
</div>

<div class="form-control">
    <label class="label" for="parent_id">
        <span class="label-text">Parent Category</span>
    </label>
    <select id="parent_id" name="parent_id"
            class="select select-bordered @error('parent_id') select-error @enderror">
        <option value="">-- No Parent --</option>
        @foreach($parents as $id => $name)
            <option value="{{ $id }}"
                {{ old('parent_id', $category->parent_id ?? '') == $id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
    @error('parent_id')
        <label class="label">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </label>
    @enderror
</div>

<div class="form-control">
    <label class="label" for="order">
        <span class="label-text">Sort Order</span>
    </label>
    <input type="number" id="order" name="order" min="0"
           class="input input-bordered @error('order') input-error @enderror"
           value="{{ old('order', $category->order ?? 0) }}">
    @error('order')
        <label class="label">
            <span class="label-text-alt text-error">{{ $message }}</span>
        </label>
    @enderror
</div>

<div class="form-control mt-4">
    <label class="cursor-pointer label justify-start">
        <input type="checkbox" name="is_active"
               class="toggle toggle-primary"
               {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
        <span class="label-text ml-2">Active Status</span>
    </label>
</div>
