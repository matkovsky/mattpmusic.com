@if ($episodes->hasPages())
    <div class="pagination">
        @if ($episodes->nextPageUrl())
            <a href="{{ $episodes->nextPageUrl() }}" class="older">Older<span> episodes</span></a>
        @endif
        @if ($episodes->previousPageUrl())
            <a href="{{ $episodes->previousPageUrl() }}" class="newer">Newer<span> episodes</span></a>
        @endif
    </div>
@endif
