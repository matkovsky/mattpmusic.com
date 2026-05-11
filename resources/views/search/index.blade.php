@extends('layouts.app')

@section('title', $query !== '' ? "Search: {$query} — " . config('app.name') : 'Search — ' . config('app.name'))

@section('content')
<section id="episodes" class="search-results">
    @if ($query === '')
        <p class="search-status">Type something into the search box to find episodes.</p>
    @elseif ($episodes->isEmpty())
        <p class="search-status">No episodes match &ldquo;{{ $query }}&rdquo;.</p>
    @else
        <p class="search-status">Results for &ldquo;{{ $query }}&rdquo;.</p>

        @foreach ($episodes as $episode)
            <article class="episode episode--result {{ $episode->tags_as_classes }}">
                <h1>
                    <a href="{{ route('episodes.show', $episode) }}">
                        {!! \App\Support\SearchSnippet::highlight($episode->title, $query) !!}
                    </a>
                </h1>
                <div class="snippet">
                    {!! \App\Support\SearchSnippet::snippet($episode->body, $query) !!}
                </div>
                <aside>
                    <time pubdate>{{ $episode->published_at->format('j M Y') }}</time>
                    @if ($episode->tags->isNotEmpty())
                        <ul class="tags">
                            @foreach ($episode->tags as $tag)
                                <li>{!! \App\Support\SearchSnippet::highlight($tag->name, $query) !!}</li>
                            @endforeach
                        </ul>
                    @endif
                </aside>
            </article>
        @endforeach

        @include('episodes._pagination')
    @endif
</section>
@endsection
