<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class DantriCrawler
{
    protected string $baseUrl = 'https://dantri.com.vn';

    protected array $categorySources = [
        ['name' => 'Thời sự',   'slug' => 'thoi-su',   'url' => 'https://dantri.com.vn/thoi-su.htm'],
        ['name' => 'Thế giới',  'slug' => 'the-gioi',  'url' => 'https://dantri.com.vn/the-gioi.htm'],
        ['name' => 'Kinh doanh','slug' => 'kinh-doanh','url' => 'https://dantri.com.vn/kinh-doanh.htm'],
        ['name' => 'Thể thao',  'slug' => 'the-thao',  'url' => 'https://dantri.com.vn/the-thao.htm'],
        ['name' => 'Giải trí',  'slug' => 'giai-tri',  'url' => 'https://dantri.com.vn/giai-tri.htm'],
    ];

    public function crawlCategoriesAndPosts(): void
    {
        foreach ($this->categorySources as $source) {
            $category = Category::updateOrCreate(
                ['slug' => $source['slug']],
                ['name' => $source['name']]
            );

            $this->crawlCategoryPosts($category, $source['url']);
        }
    }

    protected function crawlCategoryPosts(Category $category, string $url): void
    {
        $response = Http::get($url);
        if (! $response->ok()) return;

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($response->body());
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        // Dân Trí chuẩn
        $articles = $xpath->query("//article[contains(@class,'article-item')]");

        foreach ($articles as $article) {
            $a = $xpath->query(".//h3/a", $article)->item(0);
            if (! $a) continue;

            $title = trim($a->textContent);
            $href  = $a->getAttribute('href');
            if (! $title || ! $href) continue;

            $articleUrl = str_starts_with($href, 'http')
                ? $href
                : $this->baseUrl . $href;

            // Thumbnail
            $img = $xpath->query(".//img", $article)->item(0);
            $thumbnail =
                $img?->getAttribute('data-src')
                ?: $img?->getAttribute('data-original')
                ?: $img?->getAttribute('src');

            // Excerpt
            $excerptNode = $xpath->query(".//p", $article)->item(0);
            $excerpt = $excerptNode ? trim($excerptNode->textContent) : null;

            // Crawl chi tiết
            $content = $this->crawlArticleContent($articleUrl);
            if (! $content) continue;

            $post = Post::updateOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'title'        => $title,
                    'excerpt'      => $excerpt,
                    'content'      => $content,
                    'thumbnail'    => $thumbnail,
                    'status'       => 'published',
                    'published_at' => now(),
                    'user_id'      => 1,
                ]
            );

            $post->categories()->syncWithoutDetaching([$category->id]);
        }
    }

    protected function crawlArticleContent(string $url): ?string
    {
        $response = Http::get($url);
        if (! $response->ok()) return null;

        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($response->body());
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);

        $contentNode = $xpath->query("//div[contains(@class,'singular-content')]")->item(0);
        if (! $contentNode) return null;

        // Loại script / quảng cáo
        foreach ($xpath->query(".//script|.//style", $contentNode) as $node) {
            $node->parentNode->removeChild($node);
        }

        return trim($dom->saveHTML($contentNode));
    }
}
