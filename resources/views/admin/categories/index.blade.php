<x-app-layout>
    <div class="container mx-auto px-4 py-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Category Management</h1>
            <button class="btn btn-primary" onclick="categoryModal.showModal()">
                <i class="fas fa-plus mr-2"></i> Add Category
            </button>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- category table --}}
        <div class="bg-base-100 rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->position }}</td>
                                <td>{{ $category->name }}</td>
                                <td class="text-sm opacity-75">{{ $category->slug }}</td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-error' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="flex space-x-2">
                                    <button class="btn btn-sm btn-outline"
                                        onclick="editCategory({{ json_encode($category) }})">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-error">
                                            Delete
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.categories.toggle-status', $category->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $category->is_active ? 'btn-warning' : 'btn-success' }}">
                                            {{ $category->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No categories found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- category modal --}}
    <dialog id="categoryModal" class="modal">
        <div class="modal-box max-w-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="font-bold text-lg" id="modalTitle">Add New Category</h3>

            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <input type="hidden" id="categoryId" name="id">

                <div class="space-y-4 mt-4">
                    <div class="form-control">
                        <label class="label" for="name">
                            <span class="label-text">Category Name</span>
                        </label>
                        <input type="text" id="name" name="name" class="input input-bordered w-full"
                            required>
                    </div>

                    <div class="form-control w-full">
                        <label class="label" for="description">
                            <span class="label-text">Description</span>
                        </label>
                        <textarea id="description" name="description" class="textarea textarea-bordered h-24"></textarea>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text">Active</span>
                                <input type="checkbox" id="is_active" name="is_active" class="toggle toggle-primary"
                                    checked>
                            </label>
                        </div>

                        <div class="form-control w-full max-w-xs">
                            <label class="label" for="position">
                                <span class="label-text">Position</span>
                            </label>
                            <input type="number" id="position" name="position" class="input input-bordered"
                                min="0">
                        </div>
                    </div>

                    <div class="divider">SEO Settings</div>

                    <div class="form-control">
                        <label class="label" for="meta_title">
                            <span class="label-text">Meta Title</span>
                        </label>
                        <input type="text" id="meta_title" name="meta_title" class="input input-bordered w-full">
                    </div>

                    <div class="form-control">
                        <label class="label" for="meta_description">
                            <span class="label-text">Meta Description</span>
                        </label>
                        <textarea id="meta_description" name="meta_description" class="textarea textarea-bordered h-24"></textarea>
                    </div>
                </div>

                <div class="modal-action">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" onclick="categoryModal.close()" class="btn">Cancel</button>
                </div>
            </form>
        </div>
    </dialog>
</x-app-layout>

@section('scripts')
    <script>
        const categoryModal = document.getElementById('categoryModal');
        const categoryForm = document.getElementById('categoryForm');
        const modalTitle = document.getElementById('modalTitle');
        const formMethod = document.getElementById('formMethod');
        const categoryId = document.getElementById('categoryId');

        function editCategory(category) {
            modalTitle.textContent = 'Edit Category';
            formMethod.value = 'PUT';
            categoryId.value = category.id;

            // Fill form with category data
            document.getElementById('name').value = category.name;
            document.getElementById('description').value = category.description || '';
            document.getElementById('is_active').checked = category.is_active;
            document.getElementById('position').value = category.position;
            document.getElementById('meta_title').value = category.meta_title || '';
            document.getElementById('meta_description').value = category.meta_description || '';

            // Set form action
            categoryForm.action = `/admin/categories/${category.id}`;

            categoryModal.showModal();
        }

        // Reset form for new category
        categoryModal.addEventListener('close', () => {
            if (formMethod.value === 'PUT') {
                modalTitle.textContent = 'Add New Category';
                formMethod.value = 'POST';
                categoryId.value = '';
                categoryForm.reset();
                categoryForm.action = '/admin/categories';
            }
        });

        // Initialize form action for new category
        categoryForm.action = '/admin/categories';
    </script>
@endsection
