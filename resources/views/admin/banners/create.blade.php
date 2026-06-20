@extends('layouts.admin')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/views/banners_create.css') }}">
@endpush


@section('title', 'Add New Banner')
@section('header_title', 'Create Banner')



@section('content')
<div class="form-card">
    <div class="card-header">
        <h2><i class="fa-solid fa-plus-circle"></i> Upload Seasonal Campaign Banner</h2>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid">
                <div class="form-column">
                    <div class="form-group">
                        <label for="title">Banner Title (Optional)</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="e.g. Back to School Promotion">
                    </div>

                    <div class="form-group">
                        <label for="link">Redirect URL / Path (Optional)</label>
                        <input type="text" id="link" name="link" class="form-control" placeholder="e.g. /categories/stationery or https://...">
                    </div>

                    <div class="row">
                        <div class="col form-group">
                            <label for="sequence">Display Sequence</label>
                            <input type="number" id="sequence" name="sequence" class="form-control" value="0" min="0">
                        </div>
                        <div class="col form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="active">Active (Visible)</option>
                                <option value="inactive">Inactive (Hidden)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="expires_at">Expiration Date & Time (Optional)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at" class="form-control">
                    </div>
                </div>

                <div class="form-column">
                    <div class="form-group">
                        <label>Banner Asset File <span class="text-required">*</span></label>
                        <div class="banner-upload-box" onclick="document.getElementById('image').click()">
                            <div class="placeholder-content" id="bannerPlaceholder">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <p>Select Campaign Banner</p>
                                <span>Max 2MB (JPG, PNG, WEBP)</span>
                            </div>
                            <img id="bannerPreview" src="" alt="Preview" class="inline-style-1">
                            <input type="file" id="image" name="image" class="file-input" accept="image/*" required onchange="previewBannerImage(this)">
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.banners.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn"><i class="fa-solid fa-save"></i> Save Banner</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/banners.js') }}"></script>
@endpush

