@extends('layouts.admin')

@section('title', 'Edit Item')
@section('header_title', 'Edit Item: ' . $item->name)

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/items_edit.css') }}">
@endpush

@section('content')
<div class="form-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-pen-to-square"></i> Edit Item #ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</h2>
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

        <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data" class="item-form">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <!-- Left Column -->
                <div class="form-column">
                    <div class="form-group">
                        <label for="name">Item Name <span class="text-required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $item->name) }}" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="type_id">Category / Type <span class="text-required">*</span></label>
                            <select id="type_id" name="type_id" class="form-control" required>
                                <option value="">-- Select Type --</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('type_id', $item->type_id) == $type->id ? 'selected' : '' }}>
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
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $item->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Price (Ks) <span class="text-required">*</span></label>
                            <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $item->price) }}" min="0" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="stock_quantity">Stock Quantity <span class="text-required">*</span></label>
                            <input type="number" id="stock_quantity" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $item->stock_quantity) }}" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description (Optional)</label>
                        <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>

                <!-- Right Column (Image & Status) -->
                <div class="form-column">
                    <div class="form-group image-upload-group">
                        <label for="image">Main Image (Upload new to replace)</label>
                        <div class="image-preview-container" id="imagePreviewContainer">
                            @if($item->image)
                                <img id="imagePreview" src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" style="display: block;">
                                <div class="placeholder-content" id="placeholderContent" style="display: none;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p>Click to replace image</p>
                                    <span>Max 2MB (JPG, PNG)</span>
                                </div>
                            @else
                                <div class="placeholder-content" id="placeholderContent">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p>Click to upload image</p>
                                    <span>Max 2MB (JPG, PNG)</span>
                                </div>
                                <img id="imagePreview" src="" alt="Preview" style="display: none;">
                            @endif
                            
                            <input type="file" id="image" name="image" class="file-input" accept="image/jpeg,image/png,image/jpg,image/gif">
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <label>Existing Gallery Images</label>
                        @if($item->images->count() > 0)
                            <div class="gallery-grid">
                                @foreach($item->images as $galleryImg)
                                    <div class="gallery-item">
                                        <img src="{{ Storage::url($galleryImg->image_path) }}" alt="Gallery Image">
                                        <button type="button" class="btn-remove-image" onclick="deleteGalleryImage({{ $galleryImg->id }})">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted" style="font-size: 0.85rem;">No gallery images found.</p>
                        @endif
                    </div>

                    <div class="form-group mt-3">
                        <label for="gallery_images">Add New Gallery Images</label>
                        <input type="file" id="gallery_images" name="gallery_images[]" class="form-control" multiple accept="image/jpeg,image/png,image/jpg,image/gif">
                    </div>

                    <div class="form-group status-group mt-3">
                        <label for="status">Availability Status <span class="text-required">*</span></label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="active" {{ old('status', $item->status) == 'active' ? 'selected' : '' }}>Active (Available for sale)</option>
                            <option value="inactive" {{ old('status', $item->status) == 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                            <option value="out_of_stock" {{ old('status', $item->status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock (Visible but not purchasable)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.items.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary"><i class="fa-solid fa-save"></i> Update Item</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/items.js') }}"></script>
<script>
    function deleteGalleryImage(id) {
        if (confirm('Are you sure you want to remove this gallery image?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/items/image/${id}`;
            form.style.display = 'none';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endpush
