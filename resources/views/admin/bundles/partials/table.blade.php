<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Original Price</th>
                <th>Bundle Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bundles as $bundle)
                @php
                    $original_price = 0;
                    foreach($bundle->bundleItems as $bi) {
                        $original_price += $bi->item->price * $bi->quantity;
                    }
                @endphp
                <tr>
                    <td class="id-column">PKG-{{ str_pad($bundle->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        @if($bundle->image)
                            <img src="{{ Storage::url($bundle->image) }}" alt="{{ $bundle->name }}" class="table-img" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                        @else
                            <div class="no-img-placeholder" style="width:50px;height:50px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;border-radius:4px;color:#9ca3af;"><i class="fa-solid fa-box"></i></div>
                        @endif
                    </td>
                    <td>
                        <div class="fw-bold">{{ $bundle->name }}</div>
                        <div class="text-muted small">{{ $bundle->bundleItems->count() }} Items</div>
                    </td>
                    <td><del class="text-muted">{{ number_format($original_price, 0) }} Ks</del></td>
                    <td class="fw-bold text-primary">{{ number_format($bundle->bundle_price, 0) }} Ks</td>
                    <td>
                        <span class="status-badge {{ $bundle->status === 'active' ? 'status-active' : 'status-inactive' }}">
                            {{ ucfirst($bundle->status) }}
                        </span>
                    </td>
                    <td class="actions-column">
                        <a href="{{ route('admin.bundles.edit', $bundle) }}" class="btn-action edit" title="Edit">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.bundles.destroy', $bundle) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this bundle?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action delete" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No bundles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $bundles->links('vendor.pagination.custom') }}
</div>
