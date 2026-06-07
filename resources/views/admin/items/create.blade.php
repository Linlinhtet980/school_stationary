@extends('layouts.admin')

@section('title', 'Add New Item')
@section('header_title', 'Create Item')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/items_create.css') }}">
@endpush

@section('content')
<div class="form-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Add New Item</h2>
        <a href="{{ route('admin.items.index') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Items</a>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div class="alert-error">
                <strong><i class="fa-solid fa-triangle-exclamation"></i> Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="item-form">
            @csrf
            
            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="name">Item Name <span class="text-required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. A4 Copy Paper">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="type_id">Category / Type <span class="text-required">*</span></label>
                            <select id="type_id" name="type_id" class="form-control" required>
                                <option value="">-- Select Type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->category->name ?? 'Unknown' }} > {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand_id">Brand (Optional)</label>
                            <select id="brand_id" name="brand_id" class="form-control">
                                <option value="">-- No Brand --</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (Ks) <span class="text-required">*</span></label>
                            <input type="number" id="price" name="price" class="form-control" value="{{ old('price', 0) }}" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_quantity">Initial Stock <span class="text-required">*</span></label>
                            <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 0) }}" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Product details...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <!-- Right Column (Image & Status) -->
                <div class="form-column">
                    <div class="form-group image-upload-group">
                        <label for="image">Main Image (Optional)</label>
                        <div class="image-preview-container" id="imagePreviewContainer">
                            <div class="placeholder-content" id="placeholderContent">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <p>Click to upload image</p>
                                <span>Max 2MB (JPG, PNG)</span>
                            </div>
                            <img id="imagePreview" src="" alt="Preview" style="display: none;">
                            <input type="file" id="image" name="image" class="file-input" accept="image/jpeg,image/png,image/jpg,image/gif">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label for="gallery_images">Gallery Images (Max 4)</label>
                        <input type="file" id="gallery_images" name="gallery_images[]" class="form-control" multiple accept="image/jpeg,image/png,image/jpg,image/gif">
                        <small class="text-muted">You can select multiple images to show in the details page.</small>
                    </div>

                    <div class="form-group status-group mt-3">
                        <label for="status">Availability Status <span class="text-required">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Available for sale)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (Visible but not purchasable)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="reset" class="btn-outline">Reset Form</button>
                <button type="submit" class="btn-primary"><i class="fa-solid fa-save"></i> Save Item</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/items.js') }}"></script>
@endpush
