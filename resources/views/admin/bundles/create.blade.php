@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/bundles.css') }}">
@endpush




@section('title', 'Add New Bundle')

@section('content')
<div class="page-header">
    <div class="header-left">
        <a href="{{ route('admin.bundles.index') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Bundles</a>
        <h1 class="page-title">Create New Bundle</h1>
    </div>
</div>

<div class="page-content">
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.bundles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Bundle Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Bundle Items <span class="text-danger">*</span></label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th>Select Item</th>
                                            <th width="150">Quantity</th>
                                            <th width="80">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="itemsContainer">
                                        <tr class="item-row">
                                            <td>
                                                <select name="items[0][item_id]" class="form-control item-select" required>
                                                    <option value="">Select an item...</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" data-price="{{ $item->price }}">{{ $item->name }} ({{ number_format($item->price, 0) }} Ks)</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[0][quantity]" class="form-control item-qty" value="1" min="1" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">
                                                <button type="button" class="btn btn-secondary btn-sm" id="addItemBtn">
                                                    <i class="fa-solid fa-plus"></i> Add Another Item
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="mt-2 text-end">
                                <strong>Calculated Original Price: </strong> <span id="originalPriceDisplay" class="text-muted">0</span> Ks
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="bundle_price">Discounted Bundle Price (Ks) <span class="text-danger">*</span></label>
                            <input type="number" name="bundle_price" id="bundle_price" class="form-control" value="{{ old('bundle_price') }}" required min="0">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="image">Bundle Image</label>
                            <div class="image-upload-wrapper">
                                <div class="image-preview" id="imagePreview">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <span>Click or drag image here</span>
                                </div>
                                <input type="file" name="image" id="image" class="file-input" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Save Bundle</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
