<?php

namespace App\Console\Commands;

use App\Models\Episode;
use App\Models\Tag;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\HTMLToMarkdown\HtmlConverter;

class ImportTumblr extends Command
{
    protected $signature = 'import:tumblr {file : Path to Tumblr JSON export file}';
    protected $description = 'Import episodes from a Tumblr JSON export file';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (! file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($filePath), true);

        if (! $data) {
            $this->error('Failed to parse JSON file.');
            return self::FAILURE;
        }

        $posts = $data['response']['posts'] ?? $data['posts'] ?? $data;

        if (! is_array($posts)) {
            $this->error('Could not find posts array in JSON data.');
            return self::FAILURE;
        }

        $converter = new HtmlConverter([
            'strip_tags' => false,
            'hard_break' => true,
        ]);

        $redirectMap = [];

        $this->withProgressBar($posts, function ($post) use ($converter, &$redirectMap) {
            $title = $post['title'] ?? 'Untitled';
            $slug = Str::slug($title);
            $body = $post['body'] ?? '';

            // Convert HTML to Markdown
            $markdown = $converter->convert($body);

            // Extract Apple Podcast ID from [id: XXXXX] patterns
            $applePodcastId = null;
            if (preg_match('/\[id:\s*(\d+)\]/', $markdown, $matches)) {
                $applePodcastId = $matches[1];
                $markdown = preg_replace('/\[id:\s*\d+\]/', '', $markdown);
            }

            // Clean up the markdown
            $markdown = trim($markdown);

            // Parse tags
            $tags = $post['tags'] ?? [];

            // Parse published date
            $publishedAt = isset($post['timestamp'])
                ? date('Y-m-d', $post['timestamp'])
                : ($post['date'] ?? now()->toDateString());

            // Create episode
            $episode = Episode::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'body' => $markdown,
                    'apple_podcast_episode_id' => $applePodcastId,
                    'published_at' => $publishedAt,
                    'is_published' => true,
                ]
            );

            // Attach tags
            $tagIds = collect($tags)->map(function ($tagName) {
                return Tag::firstOrCreate(
                    ['slug' => Str::slug($tagName)],
                    ['name' => $tagName]
                )->id;
            });
            $episode->tags()->sync($tagIds);

            // Build redirect map
            if (isset($post['id'])) {
                $redirectMap[(string) $post['id']] = $slug;
            }
        });

        $this->newLine(2);
        $this->info('Import complete! ' . count($posts) . ' episodes imported.');

        // Output redirect map
        if (! empty($redirectMap)) {
            $this->newLine();
            $this->info('Redirect map for config/redirects.php:');
            $this->newLine();
            $this->line('return [');
            foreach ($redirectMap as $tumblrId => $episodeSlug) {
                $this->line("    '{$tumblrId}' => '{$episodeSlug}',");
            }
            $this->line('];');
        }

        return self::SUCCESS;
    }
}
