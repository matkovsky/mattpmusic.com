@extends('layouts.app')

@section('content')
<section id="episodes">
    @foreach ($episodes as $episode)
        <article class="episode {{ $episode->tags_as_classes }}">
            <h1><a href="{{ route('episodes.show', $episode) }}">{{ $episode->title }}</a></h1>
            {!! $episode->renderedBody() !!}
            @if($episode->apple_podcast_episode_id)
                <div class="apple-podcast">
                    <iframe height="175" width="100%" title="Media player"
                        src="https://embed.podcasts.apple.com/us/podcast/id279525447?i={{ $episode->apple_podcast_episode_id }}&amp;theme=light"
                        sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation-by-user-activation"
                        allow="autoplay *; encrypted-media *; clipboard-write"
                        style="border:0;border-radius:12px;width:100%;height:175px;max-width:100%;">
                    </iframe>
                </div>
            @endif
            <aside>
                <time pubdate>{{ $episode->published_at->format('j M Y') }}</time>
            </aside>
        </article>
    @endforeach

    @include('episodes._pagination')
</section>
@endsection
