@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/brands.css') }}">
    <style>
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: 500; }
        .form-control { width: 100%; max-width: 400px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-submit { background: #0d6efd; color: #fff; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-submit:hover { background: #0b5ed7; }
        .error-msg { color: #dc3545; font-size: 0.9em; display: block; margin-top: 5px; }
    </style>
@endpush

@section('title', 'Edit Shipping Rate')
@section('header_title', 'Edit Shipping Rate')

@section('content')
<div class="brand-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-pen"></i> Edit Shipping Rate</h2>
    </div>

    <form action="{{ route('admin.shipping-rates.update', $shippingRate->id) }}" method="POST" style="padding: 20px;">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>City / Region Name</label>
            <input type="text" name="region_name" class="form-control" value="{{ old('region_name', $shippingRate->region_name) }}" required>
            @error('region_name') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Base Fee (Ks) [Covers first item]</label>
            <input type="number" name="base_fee" class="form-control" value="{{ old('base_fee', $shippingRate->base_fee) }}" min="0" required>
            @error('base_fee') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Extra Fee Per Item (Ks) [For 2nd item onwards]</label>
            <input type="number" name="extra_fee_per_item" class="form-control" value="{{ old('extra_fee_per_item', $shippingRate->extra_fee_per_item) }}" min="0" required>
            @error('extra_fee_per_item') <span class="error-msg">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn-submit">Update Rate</button>
        <a href="{{ route('admin.shipping-rates.index') }}" style="margin-left:15px; color:#666;">Cancel</a>
    </form>
</div>
@endsection
