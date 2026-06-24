@extends('layouts.customer')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/customer/views/b2s_deals.css') }}">
@endpush

@section('title', 'Campus Supply - B2S Bundles')

@section('content')
<div class="b2s-hero">
    <div class="b2s-hero-content">
        <div class="b2s-badge">🎒 Back to School</div>
        <h1>B2S Bundle Deals</h1>
        <p>Everything you need, packed together — at a special price.</p>
    </div>
</div>

<div class="bundles-container">
    @if($bundles->count() > 0)
    <div class="bundles-grid">
        @foreach($bundles as $bundle)
        @php
            $previewItems = $bundle->bundleItems->take(2);
            $remainingCount = $bundle->bundleItems->count() - 2;
            $originalTotal = $bundle->bundleItems->sum(function($bi) {
                return ($bi->item->display_price ?? 0) * ($bi->quantity ?? 1);
            });
            $savings = $originalTotal - $bundle->bundle_price;
            $savingsPct = $originalTotal > 0 ? round(($savings / $originalTotal) * 100) : 0;
        @endphp
        <div class="bundle-card">
            {{-- Savings Badge --}}
            @if($savingsPct > 0)
            <div class="savings-badge">Save {{ $savingsPct }}%</div>
            @endif

            {{-- Bundle Image --}}
            <div class="bundle-img-wrap">
                @if($bundle->image)
                    <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}" class="bundle-img">
                @else
                    <div class="bundle-img-placeholder">
                        <i class="fa-solid fa-box-open"></i>
                    </div>
                @endif
            </div>

            {{-- Bundle Info --}}
            <div class="bundle-body">
                <div class="bundle-items-count">
                    <i class="fa-solid fa-layer-group"></i> {{ $bundle->bundleItems->count() }} Items Inside
                </div>
                <h3 class="bundle-name">{{ Str::limit($bundle->name, 40) }}</h3>

                {{-- 2 Item Preview --}}
                <div class="bundle-preview">
                    @foreach($previewItems as $bi)
                    <div class="preview-item">
                        <div class="preview-thumb">
                            @php $img = $bi->item->images->first(); @endphp
                            @if($img)
                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $bi->item->name }}">
                            @else
                                <i class="fa-solid fa-box"></i>
                            @endif
                        </div>
                        <div class="preview-info">
                            <span class="preview-name">{{ Str::limit($bi->item->name, 22) }}</span>
                            <span class="preview-qty">x{{ $bi->quantity }}</span>
                        </div>
                    </div>
                    @endforeach

                    @if($remainingCount > 0)
                    <button class="view-all-btn" onclick="openBundleModal({{ $bundle->id }})">
                        <i class="fa-solid fa-plus"></i> {{ $remainingCount }} more item{{ $remainingCount > 1 ? 's' : '' }}
                    </button>
                    @endif
                </div>

                {{-- Pricing --}}
                <div class="bundle-price-section">
                    @if($originalTotal > $bundle->bundle_price)
                    <div class="original-price">{{ number_format($originalTotal, 0) }} Ks</div>
                    @endif
                    <div class="bundle-price">{{ number_format($bundle->bundle_price, 0) }} Ks</div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="bundle-footer">
                <button class="btn-details" onclick="openBundleModal({{ $bundle->id }})">
                    <i class="fa-solid fa-eye"></i> View Items
                </button>
                @auth
                <form action="{{ route('cart.add-bundle', $bundle->id) }}" method="POST" class="add-form">
                    @csrf
                    <button type="submit" class="btn-add-bundle">
                        <i class="fa-solid fa-cart-shopping"></i> Add Bundle
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn-add-bundle">
                    <i class="fa-solid fa-cart-shopping"></i> Add Bundle
                </a>
                @endauth
            </div>
        </div>

        {{-- Bundle Modal Data (hidden) --}}
        <div id="bundle-data-{{ $bundle->id }}" class="bundle-modal-data" style="display:none;">
            <div class="modal-bundle-name">{{ $bundle->name }}</div>
            <div class="modal-bundle-desc">{{ $bundle->description }}</div>
            @foreach($bundle->bundleItems as $bi)
            <div class="modal-item-row">
                @php $img = $bi->item->images->first(); @endphp
                <div class="modal-item-thumb">
                    @if($img)
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $bi->item->name }}">
                    @else
                        <i class="fa-solid fa-box"></i>
                    @endif
                </div>
                <div class="modal-item-info">
                    <div class="modal-item-name">{{ $bi->item->name }}</div>
                    <div class="modal-item-meta">
                        <span class="modal-item-qty">Qty: {{ $bi->quantity }}</span>
                        <span class="modal-item-price">{{ number_format($bi->item->display_price, 0) }} Ks / each</span>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="modal-add-url">{{ auth()->check() ? route('cart.add-bundle', $bundle->id) : route('login') }}</div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrap">
        {{ $bundles->links('vendor.pagination.pure-css') }}
    </div>

    @else
    <div class="empty-bundles">
        <i class="fa-solid fa-box-open"></i>
        <h3>No Bundle Deals Yet</h3>
        <p>Check back soon for exciting bundle offers!</p>
    </div>
    @endif
</div>

{{-- Bundle Detail Modal --}}
<div id="bundleModal" class="bundle-modal-overlay" onclick="closeBundleModal(event)">
    <div class="bundle-modal">
        <button class="modal-close" onclick="closeBundleModalDirect()"><i class="fa-solid fa-xmark"></i></button>
        <div class="modal-header">
            <h2 id="modalBundleName"></h2>
            <p id="modalBundleDesc"></p>
        </div>
        <div class="modal-items-list" id="modalItemsList"></div>
        <div class="modal-footer">
            <form id="modalAddForm" method="POST">
                @csrf
                <button type="submit" class="btn-add-bundle-full">
                    <i class="fa-solid fa-cart-shopping"></i> Add All to Cart
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openBundleModal(bundleId) {
    const data = document.getElementById('bundle-data-' + bundleId);
    if (!data) return;

    document.getElementById('modalBundleName').textContent = data.querySelector('.modal-bundle-name').textContent;
    document.getElementById('modalBundleDesc').textContent = data.querySelector('.modal-bundle-desc').textContent;

    const list = document.getElementById('modalItemsList');
    list.innerHTML = '';
    data.querySelectorAll('.modal-item-row').forEach(row => {
        list.appendChild(row.cloneNode(true));
    });

    const url = data.querySelector('.modal-add-url').textContent.trim();
    document.getElementById('modalAddForm').action = url;

    document.getElementById('bundleModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeBundleModal(e) {
    if (e.target === document.getElementById('bundleModal')) {
        closeBundleModalDirect();
    }
}

function closeBundleModalDirect() {
    document.getElementById('bundleModal').classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeBundleModalDirect();
});
</script>
@endpush
