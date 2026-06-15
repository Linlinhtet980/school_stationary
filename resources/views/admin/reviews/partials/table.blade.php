<div class="table-responsive">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td class="id-column">REV-{{ str_pad($review->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="fw-bold">{{ $review->customer->name ?? 'Unknown' }}</div>
                        <div class="text-muted small">{{ $review->customer->user->email ?? '' }}</div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ Str::limit($review->item->name ?? 'Unknown', 30) }}</div>
                    </td>
                    <td>
                        <div style="color: #fbbf24;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating)
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                    </td>
                    <td>
                        <div style="max-width: 300px; white-space: normal;">
                            {{ $review->comment ?: '-' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $review->status === 'visible' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>
                    <td class="actions-column" style="min-width: 120px;">
                        <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            @if($review->status === 'visible')
                                <input type="hidden" name="status" value="hidden">
                                <button type="submit" class="btn btn-warning btn-sm" title="Hide Review">
                                    <i class="fa-solid fa-eye-slash"></i> Hide
                                </button>
                            @else
                                <input type="hidden" name="status" value="visible">
                                <button type="submit" class="btn btn-success btn-sm" title="Make Visible">
                                    <i class="fa-solid fa-eye"></i> Show
                                </button>
                            @endif
                        </form>
                        
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
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
                    <td colspan="7" class="text-center py-4 text-muted">No reviews found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $reviews->links('vendor.pagination.custom') }}
</div>
