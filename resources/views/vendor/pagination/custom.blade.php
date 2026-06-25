@if ($paginator->hasPages())
    <ul class="pagination" style="display:flex; list-style:none; gap:5px; padding:0; margin:0; justify-content: flex-end; align-items: center;">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true" aria-label="Previous">
                <span class="page-link" aria-hidden="true" style="padding: 6px 12px; border: 1px solid var(--border-color); background: #f8fafc; color: #a0aec0; border-radius: 6px;">&lsaquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous" style="padding: 6px 12px; border: 1px solid var(--border-color); background: #fff; color: var(--secondary); text-decoration: none; border-radius: 6px; transition: all 0.2s;">&lsaquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" style="padding: 6px 12px; color: #a0aec0;">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link" style="padding: 6px 12px; background: var(--primary); color: var(--secondary); border: 1px solid var(--primary); font-weight: 600; border-radius: 6px;">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}" style="padding: 6px 12px; border: 1px solid var(--border-color); background: #fff; text-decoration: none; color: var(--secondary); border-radius: 6px; transition: all 0.2s;">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next" style="padding: 6px 12px; border: 1px solid var(--border-color); background: #fff; color: var(--secondary); text-decoration: none; border-radius: 6px; transition: all 0.2s;">&rsaquo;</a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true" aria-label="Next">
                <span class="page-link" aria-hidden="true" style="padding: 6px 12px; border: 1px solid var(--border-color); background: #f8fafc; color: #a0aec0; border-radius: 6px;">&rsaquo;</span>
            </li>
        @endif
    </ul>
@endif
