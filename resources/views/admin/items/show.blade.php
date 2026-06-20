@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/items_show.css') }}">
@endpush


@section('title', 'Item Details')
@section('header_title', 'Item Details')



@section('content')
<div class="show-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-circle-info"></i> Item Details: ITM-{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</h2>
        <div class="header-actions">
            <a href="{{ route('admin.items.index') }}" class="btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Items</a>
            <a href="{{ route('admin.items.edit', $item->id) }}" class="btn-primary"><i class="fa-solid fa-pen"></i> Edit Item</a>
        </div>
    </div>

    <div class="card-body">
        <div class="detail-grid">
            <!-- Left: Image -->
            <div class="detail-image-wrapper">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="detail-image">
                @else
                    <div class="detail-image-placeholder">
                        <i class="fa-solid fa-image"></i>
                        <p>No Image Available</p>
                    </div>
                @endif
            </div>

            <!-- Gallery Images -->
            @if($item->images->count() > 0)
            <div class="gallery-wrapper mt-3">
                <h4 class="mb-2 text-muted inline-style-23" >Gallery Images</h4>
                <div class="gallery-grid-show">
                    @foreach($item->images as $galleryImg)
                        <div class="gallery-item-show">
                            <img src="{{ Storage::url($galleryImg->image_path) }}" alt="Gallery Image">
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right: Info -->
        <div class="detail-info">
                <div class="info-header">
                    <h1 class="info-title">{{ $item->name }}</h1>
                    <div class="info-badges">
                        @if($item->status === 'active')
                            <span class="badge badge-active">Active</span>
                        @elseif($item->status === 'out_of_stock')
                            <span class="badge badge-warning">Out of Stock</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif

                        @if($item->brand)
                            <span class="badge badge-brand"><i class="fa-solid fa-copyright"></i> {{ $item->brand->name }}</span>
                        @endif
                    </div>
                </div>

                <div class="price-stock-box">
                    <div class="price-block">
                        <span class="label">Price Range</span>
                        <span class="value">{{ $item->price_range }}</span>
                    </div>
                    <div class="divider"></div>
                    <div class="stock-block">
                        <span class="label">Total Stock</span>
                        <span class="value {{ $item->total_stock <= 0 ? 'text-danger' : 'text-success' }}">
                            {{ number_format($item->total_stock) }} Units
                        </span>
                    </div>
                </div>

                <!-- Variants Table -->
                @if($item->variants->count() > 0)
                <div class="variants-box mt-4">
                    <h4 class="mb-3"><i class="fa-solid fa-list"></i> Item Variants</h4>
                    <table class="table inline-style-24" >
                        <thead>
                            <tr class="inline-style-25">
                                <th class="inline-style-26">Unit/Label</th>
                                <th class="inline-style-27">Color</th>
                                <th class="inline-style-28">Size</th>
                                <th class="inline-style-29">Price</th>
                                <th class="inline-style-30">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->variants as $variant)
                            <tr class="inline-style-31">
                                <td class="inline-style-32">{{ $variant->unit_label }} (x{{ $variant->unit_qty }})</td>
                                <td class="inline-style-33">{{ $variant->color ?: '-' }}</td>
                                <td class="inline-style-34">{{ $variant->size ?: '-' }}</td>
                                <td class="inline-style-35">{{ number_format($variant->price, 2) }} Ks</td>
                                <td class="inline-style-36">
                                    <span class="{{ $variant->stock_quantity <= 0 ? 'text-danger' : 'text-success' }}">
                                        {{ $variant->stock_quantity }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <div class="info-list">
                    <div class="info-item">
                        <div class="info-label"><i class="fa-solid fa-tags"></i> Category / Type</div>
                        <div class="info-value">{{ $item->type->category->name ?? 'N/A' }} > {{ $item->type->name ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="info-item full-width">
                        <div class="info-label"><i class="fa-solid fa-align-left"></i> Description</div>
                        <div class="info-value description-text">
                            {{ $item->description ?: 'No description provided for this item.' }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label"><i class="fa-solid fa-calendar-plus"></i> Added On</div>
                        <div class="info-value">{{ $item->created_at ? $item->created_at->format('d M Y, h:i A') : 'N/A' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label"><i class="fa-solid fa-clock-rotate-left"></i> Last Updated</div>
                        <div class="info-value">{{ $item->updated_at ? $item->updated_at->format('d M Y, h:i A') : 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
