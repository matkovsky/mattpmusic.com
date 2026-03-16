<?php

namespace Database\Seeders;

use App\Models\Episode;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EpisodeSeeder extends Seeder
{
    public function run(): void
    {
        $episodes = json_decode(
            file_get_contents(storage_path('app/episodes.json')),
            true
        );

        $redirects = [];

        foreach ($episodes as $data) {
            // Build Markdown body
            $body = '';

            if (! empty($data['description'])) {
                $body .= $data['description'] . "\n\n";
            }

            if (! empty($data['tracks'])) {
                foreach ($data['tracks'] as $i => $track) {
                    $body .= ($i + 1) . '. ' . $track . "\n";
                }
            }

            $body = trim($body);

            // Parse date
            $publishedAt = Carbon::parse($data['date']);

            $slug = Str::slug($data['title']);

            $episode = Episode::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $data['title'],
                    'body' => $body,
                    'apple_podcast_episode_id' => $data['apple_podcast_id'] ?? null,
                    'published_at' => $publishedAt,
                    'is_published' => true,
                ]
            );

            // Extract tags from classes (e.g. "episode regular_episode" -> "regular episode")
            $classes = $data['classes'] ?? '';
            $tagSlugs = collect(explode(' ', $classes))
                ->filter(fn ($c) => $c !== 'episode' && ! empty($c))
                ->values();

            $tagIds = $tagSlugs->map(function ($slug) {
                $name = str_replace('_', ' ', $slug);
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                )->id;
            });

            $episode->tags()->sync($tagIds);

            // Build redirect map
            if (! empty($data['tumblr_id'])) {
                $redirects[$data['tumblr_id']] = $slug;
            }
        }

        // Output redirect map
        $this->command->info('Redirect map for config/redirects.php:');
        $this->command->newLine();

        $map = "<?php\n\nreturn [\n";
        foreach ($redirects as $tumblrId => $episodeSlug) {
            $map .= "    '{$tumblrId}' => '{$episodeSlug}',\n";
        }
        $map .= "];\n";

        file_put_contents(config_path('redirects.php'), $map);
        $this->command->info('Written to config/redirects.php');
    }
}
