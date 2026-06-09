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
                <div class="form-column">
                    <div class="form-group">
                        <label for="name">Item Name <span class="text-required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
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

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                    </div>
                    
                    <div class="form-group status-group">
                        <label for="status">Availability Status <span class="text-required">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="out_of_stock" {{ old('status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-group image-upload-group">
                        <label>Main Image (Optional)</label>
                        <div class="image-preview-container" onclick="document.getElementById('image').click()">
                            <div class="placeholder-content" id="imagePlaceholder">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <p>Click to upload image</p>
                                <span>Max 2MB (JPG, PNG)</span>
                            </div>
                            <img id="imagePreview" src="" alt="Preview" style="display: none;">
                            <input type="file" id="image" name="image" class="file-input" accept="image/jpeg,image/png,image/jpg" onchange="previewMainImage(this)">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Gallery Images (Multiple)</label>
                        <input type="file" name="gallery_images[]" class="form-control" accept="image/jpeg,image/png,image/jpg" multiple>
                    </div>
                </div>
            </div>

            <div class="variants-section">
                <h3><i class="fa-solid fa-sitemap"></i> Item Variants (Pricing & Stock)</h3>
                
                <div class="variants-table-container">
                    <table class="variants-table">
                        <thead>
                            <tr>
                                <th>Unit Label</th>
                                <th>Unit Qty</th>
                                <th>Color</th>
                                <th>Size</th>
                                <th>Price <span class="text-required">*</span></th>
                                <th>Stock Qty <span class="text-required">*</span></th>
                                <th>SKU</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="variantsBody">
                            </tbody>
                    </table>
                </div>
                
                <button type="button" class="btn-outline btn-add-variant" onclick="addVariantRow()">
                    <i class="fa-solid fa-plus"></i> Add Another Variant
                </button>
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