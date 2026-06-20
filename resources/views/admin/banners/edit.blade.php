@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/banners_create.css') }}">
@endpush


@section('title', 'Edit Banner')
@section('header_title', 'Modify Banner')



@section('content')
<div class="form-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-pen-to-square"></i> Edit Campaign Banner</h2>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label for="title">Banner Title (Optional)</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $banner->title) }}">
                    </div>

                    <div class="form-group">
                        <label for="link">Redirect URL / Path (Optional)</label>
                        <input type="text" id="link" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label for="sequence">Display Sequence</label>
                            <input type="number" id="sequence" name="sequence" class="form-control" value="{{ old('sequence', $banner->sequence) }}" min="0">
                        </div>
                        <div class="col form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active" {{ $banner->status === 'active' ? 'selected' : '' }}>Active (Visible)</option>
                                <option value="inactive" {{ $banner->status === 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="expires_at">Expiration Date & Time (Optional)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" class="form-control" value="{{ $banner->expires_at ? \Carbon\Carbon::parse($banner->expires_at)->format('Y-m-d\TH:i') : '' }}">
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-group">
                        <label>Banner Asset File (Upload new to replace)</label>
                        <div class="banner-upload-box" onclick="document.getElementById('image').click()">
                            <img id="bannerPreview" src="{{ Storage::url($banner->image_path) }}" alt="Current Banner" style="display: block;">
                            <input type="file" id="image" name="image" class="file-input" accept="image/*" onchange="previewBannerImage(this)">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.banners.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Update Banner</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/banners.js') }}"></script>
@endpush

