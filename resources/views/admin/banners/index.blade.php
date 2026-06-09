@extends('layouts.admin')

@section('title', 'Banners Management')
@section('header_title', 'Home Banners')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/banners_index.css') }}">
@endpush

@section('content')
<div class="banner-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-images"></i> Homepage Banners</h2>
        <a href="{{ route('admin.banners.create') }}" class="btn"><i class="fa-solid fa-plus"></i> Add New Banner</a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="banner-table">
            <thead>
                <tr>
                    <th>Sequence</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Redirect Link</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $banner)
                <tr>
                    <td><span class="sequence-badge">#{{ $banner->sequence }}</span></td>
                    <td>
                        <img src="{{ Storage::url($banner->image_path) }}" alt="Banner" class="banner-thumb">
                    </td>
                    <td><strong>{{ $banner->title ?: 'Untitled Banner' }}</strong></td>
                    <td>
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" style="color: var(--secondary);">{{ $banner->link }}</a>
                        @else
                            <span class="text-muted">— No Link —</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $banner->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                            {{ ucfirst($banner->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn-icon btn-edit" title="Edit Banner">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this banner?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                        <i class="fa-regular fa-image" style="font-size: 2.5rem; margin-bottom: 10px; display:block;"></i>
                        No campaign banners found. Start adding ones!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection