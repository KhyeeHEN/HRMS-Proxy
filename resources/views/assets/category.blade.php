@extends('layout')

@section('title', 'Asset Categories')

@section('content')
<link href="{{ asset('css/search.css') }}" rel="stylesheet">

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Asset Categories</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <a href="#" class="d-none d-sm-inline-block btn btn-sm ml-2 shadow-sm"
            style="color: #ffffff; background-color: #00aeef;" data-toggle="modal" data-target="#addCategoryModal">
            <i class="fas fa-plus fa-sm mr-1" style="color: white;"></i> Add New Category
        </a>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $counter = 1; @endphp
                            @foreach($categories as $category)
                            <tr style="font-size: 14px;">
                                <td>{{ $counter++ }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                <button class="btn btn-warning btn-sm edit-btn" data-toggle="modal" data-target="#editCategoryModal" data-id="{{ $category->id }}" data-name="{{ $category->name }}">
                                    <i class="fas fa-edit fa-sm" style="color: white;"></i>
                                </button>

                                    <form action="{{ route('asset-categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" data-id="{{ $category->id }}">
                                            <i class="fas fa-trash-alt fa-sm" style="color: white;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($categories->isEmpty())
                                <tr>
                                    <td colspan="5">No categories found</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('asset-categories.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="name">Category Name</label>
                    <input type="text" name="name" class="form-control mb-3" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="edit-name">Category Name</label>
                    <input type="text" name="name" id="edit-name" class="form-control mb-3" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Handle delete modal
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var form = $('#deleteForm');
            var url = '{{ url("asset-categories") }}/' + id;
            form.attr('action', url);
        });

        // Handle edit modal
        $('.edit-btn').on('click', function () {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var form = $('#editCategoryForm');
            var url = '{{ url("asset-categories") }}/' + id;

            $('#edit-name').val(name);
            form.attr('action', url);
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var form = $('#deleteForm');
            var url = '{{ url("asset-categories") }}/' + id;
            form.attr('action', url);
        });
    });
</script>

<script>
    // Auto-hide success message
    setTimeout(() => {
        const alert = document.querySelector('.alert-success');
        if (alert) alert.style.display = 'none';
    }, 3000);
</script>
@endsection
