@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/brands.css') }}">
    <style>
        .table-responsive {
            overflow-x: auto;
            margin-top: 1rem;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }
        .data-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        .btn-add {
            background: #ffc107;
            color: #000;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-add:hover {
            background: #ffca2c;
        }
        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        .btn-edit { background: #0dcaf0; }
        .btn-delete { background: #dc3545; }
        .form-inline { display: inline-block; }
    </style>
@endpush

@section('title', 'Shipping Rates')
@section('header_title', 'Shipping Rates Management')

@section('content')
<div class="brand-card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h2><i class="fa-solid fa-truck"></i> Shipping Rates</h2>
        <a href="{{ route('admin.shipping-rates.create') }}" class="btn-add">
            <i class="fa-solid fa-plus"></i> Add New Rate
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="padding: 15px; background-color: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>City / Region</th>
                    <th>Base Fee (Ks)</th>
                    <th>Extra Fee Per Item (Ks)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rates as $index => $rate)
                    <tr>
                        <td>{{ ($rates->currentPage() - 1) * $rates->perPage() + $loop->iteration }}</td>
                        <td>{{ $rate->region_name }}</td>
                        <td>{{ number_format($rate->base_fee) }} Ks</td>
                        <td>{{ number_format($rate->extra_fee_per_item) }} Ks</td>
                        <td>
                            <a href="{{ route('admin.shipping-rates.edit', $rate->id) }}" class="btn-icon btn-edit" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.shipping-rates.destroy', $rate->id) }}" method="POST" onsubmit="event.preventDefault(); showConfirmModal('Are you sure you want to delete this shipping rate?', () => this.submit());" class="form-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">No shipping rates found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($rates instanceof \Illuminate\Pagination\LengthAwarePaginator && $rates->hasPages())
        <div class="pagination-container">
            {{ $rates->links() }}
        </div>
    @endif
</div>
@endsection
