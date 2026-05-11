<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
        ]);

        $query = trim($data['q'] ?? '');

        if ($query === '') {
            $episodes = Episode::query()->whereRaw('1 = 0')->simplePaginate(10);

            return view('search.index', [
                'episodes' => $episodes,
                'query' => $query,
            ]);
        }

        $pattern = '%' . addcslashes($query, '%_\\') . '%';

        $episodes = Episode::published()
            ->with('tags')
            ->where(function ($w) use ($pattern) {
                $w->where('title', 'like', $pattern)
                    ->orWhere('body', 'like', $pattern)
                    ->orWhereHas('tags', fn ($t) => $t->where('name', 'like', $pattern));
            })
            ->latest('published_at')
            ->simplePaginate(10)
            ->withQueryString();

        return view('search.index', [
            'episodes' => $episodes,
            'query' => $query,
        ]);
    }
}
