<?php

namespace App\Http\Controllers;

use App\Models\Episode;

class EpisodeController extends Controller
{
    public function index()
    {
        $episodes = Episode::published()
            ->with('tags')
            ->latest('published_at')
            ->simplePaginate(10);

        return view('episodes.index', compact('episodes'));
    }

    public function show(Episode $episode)
    {
        if (! $episode->is_published || $episode->published_at->isFuture()) {
            abort(404);
        }

        $episode->load('tags');

        return view('episodes.show', compact('episode'));
    }
}
