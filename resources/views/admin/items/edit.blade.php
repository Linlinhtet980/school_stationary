@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/items_edit.css') }}">
@endpush


@section('title', 'Edit Item')
@section('header_title', 'Edit Item: ' . $item->name)



@section('content')
    <div class="form-card">
        <div class="card-header">
            <h2><i class="fa-solid fa-pen-to-square"></i> Edit Item #ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</h2>
            <a href="{{ route('admin.items.index') }}" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to
                Items</a>
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

            <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data"
                class="item-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="name">Item Name <span class="text-required">*</span></label>
                            <input type="text" id="name" name="name" class="form-control"
                                value="{{ old('name', $item->name) }}" required>
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

                        <div class="form-group">
                            <label for="description">Description (Optional)</label>
                            <textarea id="description" name="description" class="form-control"
                                rows="4">{{ old('description', $item->description) }}</textarea>
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-group image-upload-group">
                            <label for="image">Main Image (Upload new to replace)</label>
                            <div class="image-preview-container" id="imagePreviewContainer"
                                onclick="document.getElementById('image').click()">
                                @if($item->image)
                                    <img id="imagePreview" src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}"
                                        style="display: block; width: 100%; height: 100%; object-fit: cover; position: absolute;">
                                    <div class="placeholder-content inline-style-17"  id="placeholderContent">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <p>Click to replace image</p>
                                    </div>
                                @else
                                    <div class="placeholder-content" id="placeholderContent">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <p>Click to upload image</p>
                                    </div>
                                    <img id="imagePreview" src="" alt="Preview"
                                        class="inline-style-18">
                                @endif
                                <input type="file" id="image" name="image" class="file-input"
                                    accept="image/jpeg,image/png,image/jpg/webp" onchange="previewMainImage(this)">
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Existing Gallery Images</label>
                            @if($item->images->count() > 0)
                                <div class="gallery-grid">
                                    @foreach($item->images as $galleryImg)
                                        <div class="gallery-item">
                                            <img src="{{ Storage::url($galleryImg->image_path) }}" alt="Gallery Image">
                                            <button type="button" class="btn-remove-image"
                                                onclick="deleteGalleryImage({{ $galleryImg->id }}, '{{ csrf_token() }}')">
                                                <i class="fa-solid fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted inline-style-19" >No gallery images found.</p>
                            @endif
                        </div>

                        <div class="form-group mt-3">
                            <label for="gallery_images">Add New Gallery Images</label>
                            <input type="file" id="gallery_images" name="gallery_images[]" class="form-control" multiple
                                accept="image/jpeg,image/png,image/jpg/webp">
                        </div>

                        <div class="form-group status-group mt-3">
                            <label for="status">Availability Status <span class="text-required">*</span></label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active" {{ old('status', $item->status) == 'active' ? 'selected' : '' }}>Active
                                </option>
                                <option value="inactive" {{ old('status', $item->status) == 'inactive' ? 'selected' : '' }}>
                                    Inactive</option>
                                <option value="out_of_stock" {{ old('status', $item->status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
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
                                    <th>Current Stock</th>
                                    <th>Add Stock</th>
                                    <th>SKU</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @if ($errors->any())
                                <div
                                    class="inline-style-20">
                                    <strong class="inline-style-21">⚠️ ဖြည့်စွက်ချက် မှားယွင်းနေပါသည်
                                        -</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <tbody id="variantsBody">
                                @foreach($item->variants as $index => $variant)
                                    <tr class="variant-row">
                                        {{-- Hidden: pass existing variant ID so controller can update instead of delete+recreate --}}
                                        <input type="hidden" name="variants[{{ $index }}][variant_id]" value="{{ $variant->id }}">
                                        <td><input type="text" name="variants[{{ $index }}][unit_label]" class="form-control"
                                                value="{{ $variant->unit_label }}"></td>
                                        <td><input type="number" name="variants[{{ $index }}][unit_qty]" class="form-control"
                                                value="{{ $variant->unit_qty }}" min="1"></td>
                                        <td><input type="text" name="variants[{{ $index }}][color]" class="form-control"
                                                value="{{ $variant->color }}"></td>
                                        <td><input type="text" name="variants[{{ $index }}][size]" class="form-control"
                                                value="{{ $variant->size }}"></td>
                                        <td><input type="number" step="0.01" name="variants[{{ $index }}][price]"
                                                class="form-control" value="{{ $variant->price }}" required min="0"></td>
                                        <td>
                                            {{-- Show current stock (read-only display) --}}
                                            <span style="font-weight:700; color:#0B2149;">{{ $variant->stock_quantity }}</span>
                                        </td>
                                        <td>
                                            {{-- Add Stock: ဤနေရာတွင် ထည့်သော ဂဏန်း ကို ရှိပြီးသား stock ပေါ် ထပ်ပေါင်းမည် --}}
                                            <input type="number" step="1" name="variants[{{ $index }}][add_stock]"
                                                class="form-control" value="0" min="0" placeholder="+0">
                                        </td>
                                        <td><input type="text" name="variants[{{ $index }}][sku]" class="form-control"
                                                value="{{ $variant->sku }}"></td>
                                        <td class="inline-style-22">
                                            <button type="button" class="btn-remove-row" onclick="removeVariantRow(this)"><i
                                                    class="fa-solid fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn-outline btn-add-variant" onclick="addVariantRow()"><i
                            class="fa-solid fa-plus"></i> Add Another Variant</button>
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
@endpush