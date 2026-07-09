@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/bundles.css') }}">
@endpush




@section('title', 'Edit Bundle')

@section('content')
<div class="page-header">
    <div class="header-left">
        <a href="{{ route('admin.bundles.index') }}" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Bundles</a>
        <h1 class="page-title">Edit Bundle: {{ $bundle->name }}</h1>
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

            <form action="{{ route('admin.bundles.update', $bundle) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="name">Bundle Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $bundle->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $bundle->description) }}</textarea>
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
                                        @foreach($bundle->bundleItems as $index => $bi)
                                        <tr class="item-row">
                                            <td>
                                                <select name="items[{{ $index }}][item_id]" class="form-control item-select" required>
                                                    <option value="">Select an item...</option>
                                                    @foreach($items as $item)
                                                        <option value="{{ $item->id }}" data-price="{{ $item->display_price }}" {{ $bi->item_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }} ({{ number_format($item->display_price, 0) }} Ks)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" name="items[{{ $index }}][quantity]" class="form-control item-qty" value="{{ $bi->quantity }}" min="1" required>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
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
                            <input type="number" name="bundle_price" id="bundle_price" class="form-control" value="{{ old('bundle_price', $bundle->bundle_price) }}" required min="0">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status', $bundle->status) == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $bundle->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="image">Bundle Image</label>
                            <div class="image-upload-wrapper">
                                <div class="image-preview" id="imagePreview">
                                    @if($bundle->image)
                                        <img src="{{ Storage::url($bundle->image) }}" style="max-width: 100%; max-height: 200px; border-radius: 8px;">
                                    @else
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <span>Click or drag image here</span>
                                    @endif
                                </div>
                                <input type="file" name="image" id="image" class="file-input" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Bundle</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const itemsContainer = document.getElementById('itemsContainer');
        const addItemBtn = document.getElementById('addItemBtn');
        const originalPriceDisplay = document.getElementById('originalPriceDisplay');
        let itemIndex = {{ $bundle->bundleItems->count() }};

        function calculateOriginalPrice() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const select = row.querySelector('.item-select');
                const qtyInput = row.querySelector('.item-qty');
                
                if (select && select.selectedIndex > 0) {
                    const price = parseFloat(select.options[select.selectedIndex].getAttribute('data-price')) || 0;
                    const qty = parseInt(qtyInput.value) || 0;
                    total += price * qty;
                }
            });
            originalPriceDisplay.textContent = new Intl.NumberFormat().format(total);
        }

        // Attach event listeners using event delegation
        itemsContainer.addEventListener('change', function(e) {
            if (e.target.classList.contains('item-select')) {
                calculateOriginalPrice();
            }
        });

        itemsContainer.addEventListener('input', function(e) {
            if (e.target.classList.contains('item-qty')) {
                calculateOriginalPrice();
            }
        });

        itemsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const row = e.target.closest('.item-row');
                if (document.querySelectorAll('.item-row').length > 1) {
                    row.remove();
                    calculateOriginalPrice();
                } else {
                    showAlertModal('You must have at least one item in the bundle.', 'Validation Error');
                }
            }
        });

        addItemBtn.addEventListener('click', function () {
            const firstRow = document.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);
            
            // Update names with new index
            const select = newRow.querySelector('.item-select');
            select.name = `items[${itemIndex}][item_id]`;
            select.value = ""; // Reset selection
            
            const qty = newRow.querySelector('.item-qty');
            qty.name = `items[${itemIndex}][quantity]`;
            qty.value = "1"; // Reset quantity
            
            itemsContainer.appendChild(newRow);
            itemIndex++;
            calculateOriginalPrice();
        });

        // Initial calculation
        calculateOriginalPrice();

        // Image Upload Preview and Click
        const imagePreview = document.getElementById('imagePreview');
        const imageInput = document.getElementById('image');

        if(imagePreview && imageInput) {
            imagePreview.addEventListener('click', () => {
                imageInput.click();
            });

            imageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.innerHTML = `<img src="${e.target.result}" style="max-width: 100%; max-height: 200px; border-radius: 8px;">`;
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
            
            // Drag and Drop
            imagePreview.addEventListener('dragover', (e) => {
                e.preventDefault();
                imagePreview.style.borderColor = 'var(--primary)';
                imagePreview.style.backgroundColor = '#f8f9fa';
            });
            imagePreview.addEventListener('dragleave', () => {
                imagePreview.style.borderColor = '#CBD5E0';
                imagePreview.style.backgroundColor = 'transparent';
            });
            imagePreview.addEventListener('drop', (e) => {
                e.preventDefault();
                imagePreview.style.borderColor = '#CBD5E0';
                imagePreview.style.backgroundColor = 'transparent';
                if (e.dataTransfer.files.length) {
                    imageInput.files = e.dataTransfer.files;
                    const event = new Event('change');
                    imageInput.dispatchEvent(event);
                }
            });
        }
    });
</script>
@endpush
