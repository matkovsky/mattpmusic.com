<?php

namespace Tests\Feature;

use App\Models\Episode;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    private function makeEpisode(array $attrs = []): Episode
    {
        return Episode::create(array_merge([
            'title' => 'Episode '.fake()->unique()->numberBetween(1, 1_000_000),
            'slug' => 'ep-'.fake()->unique()->numberBetween(1, 1_000_000),
            'body' => 'placeholder body',
            'published_at' => now()->subDay(),
            'is_published' => true,
        ], $attrs));
    }

    public function test_title_match_returns_episode(): void
    {
        $hit = $this->makeEpisode(['title' => 'Deep Techno Voyage', 'slug' => 'deep-techno-voyage']);
        $miss = $this->makeEpisode(['title' => 'Sunny Pop Hits', 'slug' => 'sunny-pop-hits']);

        $response = $this->get('/search?q=techno');

        $response->assertOk();
        $response->assertSee('Deep ', false);
        $response->assertSee('Voyage', false);
        $response->assertDontSee('Sunny Pop Hits');
    }

    public function test_body_match_highlights_snippet(): void
    {
        $this->makeEpisode([
            'title' => 'Mix 100',
            'slug' => 'mix-100',
            'body' => 'Opening with a smooth groove, then **Adriatique - Sunday Vibes** rolls in around the midpoint.',
        ]);

        $response = $this->get('/search?q=Adriatique');

        $response->assertOk();
        $response->assertSee('<mark>Adriatique</mark>', false);
    }

    public function test_tag_name_match_returns_episode(): void
    {
        $tag = Tag::create(['name' => 'Progressive']);
        $episode = $this->makeEpisode(['title' => 'Untagged-Looking Title', 'slug' => 'untagged-looking-title']);
        $episode->tags()->attach($tag);

        $other = $this->makeEpisode(['title' => 'Other', 'slug' => 'other']);

        $response = $this->get('/search?q=progressive');

        $response->assertOk();
        $response->assertSee('Untagged-Looking Title');
        $response->assertDontSee('>Other<', false);
    }

    public function test_unpublished_episodes_are_excluded(): void
    {
        $this->makeEpisode([
            'title' => 'Hidden Techno Demo',
            'slug' => 'hidden-techno-demo',
            'is_published' => false,
        ]);

        $this->makeEpisode([
            'title' => 'Future Techno Demo',
            'slug' => 'future-techno-demo',
            'published_at' => now()->addDay(),
        ]);

        $response = $this->get('/search?q=techno');

        $response->assertOk();
        $response->assertDontSee('Hidden Techno Demo');
        $response->assertDontSee('Future Techno Demo');
    }

    public function test_like_wildcards_in_query_are_escaped(): void
    {
        $this->makeEpisode(['title' => 'Mix Number 100', 'slug' => 'mix-number-100']);
        $this->makeEpisode(['title' => 'Mix Number 200', 'slug' => 'mix-number-200']);

        $response = $this->get('/search?q='.urlencode('100%'));

        $response->assertOk();
        $response->assertDontSee('Mix Number 200');
    }

    public function test_empty_query_renders_prompt(): void
    {
        $this->makeEpisode(['title' => 'Anything', 'slug' => 'anything']);

        $response = $this->get('/search');

        $response->assertOk();
        $response->assertSee('Type something');
        $response->assertDontSee('Anything');
    }

    public function test_pagination_preserves_query(): void
    {
        for ($i = 1; $i <= 12; $i++) {
            $this->makeEpisode([
                'title' => "Techno Mix {$i}",
                'slug' => "techno-mix-{$i}",
                'published_at' => now()->subDays($i),
            ]);
        }

        $response = $this->get('/search?q=techno');

        $response->assertOk();
        $response->assertSee('q=techno', false);
    }
}
