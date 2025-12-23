<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Keyword;
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
            if (! $a instanceof \DOMElement) continue;

            $title = trim($a->textContent);
            $href  = $a->getAttribute('href');
            if (! $title || ! $href) continue;

            $articleUrl = str_starts_with($href, 'http')
                ? $href
                : $this->baseUrl . $href;

            // Thumbnail
            $img = $xpath->query(".//img", $article)->item(0);
            $thumbnail = null;
            if ($img instanceof \DOMElement) {
                $thumbnail =
                    $img->getAttribute('data-src')
                    ?: $img->getAttribute('data-original')
                    ?: $img->getAttribute('src');
            }

            // Excerpt
            $excerptNode = $xpath->query(".//p", $article)->item(0);
            $excerpt = $excerptNode ? trim($excerptNode->textContent) : null;

            // Crawl chi tiết
            $detail = $this->crawlArticleDetail($articleUrl);
            if (! $detail) continue;

            $content = $detail['content'];
            $keywordNames = $detail['keywords'];

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

            if ($keywordNames !== []) {
                $keywordIds = [];
                foreach ($keywordNames as $name) {
                    $slug = Str::slug($name);
                    if ($slug === '') {
                        continue;
                    }

                    $keyword = Keyword::updateOrCreate(
                        ['slug' => $slug],
                        ['name' => $name]
                    );
                    $keywordIds[] = $keyword->id;
                }

                if ($keywordIds !== []) {
                    $post->keywords()->syncWithoutDetaching(array_values(array_unique($keywordIds)));
                }
            }
        }
    }

    /**
     * @return array{content: string, keywords: array<int, string>}|null
     */
    protected function crawlArticleDetail(string $url): ?array
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

        $keywords = $this->extractKeywordsFromArticlePage($xpath);

        // Loại script / quảng cáo
        foreach ($xpath->query(".//script|.//style", $contentNode) as $node) {
            $node->parentNode->removeChild($node);
        }

        // Chuẩn hoá ảnh lazy-load (data-src/data-original/...) thành src để lưu DB render được ngay.
        foreach ($xpath->query('.//img', $contentNode) as $img) {
            if (! $img instanceof \DOMElement) {
                continue;
            }

            $dataOriginal = trim((string) $img->getAttribute('data-original'));
            $dataSrc = trim((string) $img->getAttribute('data-src'));
            $src = trim((string) $img->getAttribute('src'));
            $bestSrc = $dataOriginal ?: $dataSrc ?: $src;

            if ($bestSrc !== '') {
                $img->setAttribute('src', $this->normalizeUrl($bestSrc));
            }

            $dataSrcset = trim((string) $img->getAttribute('data-srcset'));
            if ($dataSrcset !== '') {
                // data-srcset thường có dạng: "url 1x, url 2x".
                $parts = array_map('trim', explode(',', $dataSrcset));
                $normalizedParts = [];
                foreach ($parts as $part) {
                    if ($part === '') {
                        continue;
                    }
                    // Tách "url" và "descriptor" (1x/2x/1020w...)
                    $segments = preg_split('/\s+/', $part, 2);
                    $urlPart = $segments[0] ?? '';
                    $descriptor = $segments[1] ?? '';
                    if ($urlPart === '') {
                        continue;
                    }
                    $normalized = $this->normalizeUrl($urlPart) . ($descriptor !== '' ? ' ' . $descriptor : '');
                    $normalizedParts[] = $normalized;
                }

                if ($normalizedParts !== []) {
                    $img->setAttribute('srcset', implode(', ', $normalizedParts));
                }
            }
        }

        // Lưu inner HTML để không mang theo wrapper "singular-content".
        $content = trim($this->getInnerHtml($dom, $contentNode));
        if ($content === '') {
            return null;
        }

        return [
            'content' => $content,
            'keywords' => $keywords,
        ];
    }

    protected function getInnerHtml(DOMDocument $dom, \DOMNode $node): string
    {
        $html = '';

        foreach ($node->childNodes as $child) {
            $html .= $dom->saveHTML($child);
        }

        return $html;
    }

    /**
     * @return array<int, string>
     */
    protected function extractKeywordsFromArticlePage(DOMXPath $xpath): array
    {
        $names = [];

        // 1) meta[name=keywords]
        $meta = $xpath->query("//meta[translate(@name,'ABCDEFGHIJKLMNOPQRSTUVWXYZ','abcdefghijklmnopqrstuvwxyz')='keywords']/@content")->item(0);
        if ($meta) {
            $raw = trim((string) $meta->nodeValue);
            if ($raw !== '') {
                $parts = preg_split('/\s*[,;|]\s*/u', $raw) ?: [];
                foreach ($parts as $part) {
                    $names[] = $part;
                }
            }
        }

        // 2) Tag list hiển thị trên trang (class thay đổi theo layout)
        $tagAnchors = $xpath->query(
            "//a[contains(@href,'/tag/')"
            . " or contains(@href,'/tag.htm')"
            . " or contains(concat(' ', normalize-space(@class), ' '), ' tag ')"
            . " or contains(concat(' ', normalize-space(@class), ' '), ' tags ')"
            . "]"
        );

        foreach ($tagAnchors as $a) {
            $text = trim((string) $a->textContent);
            if ($text !== '') {
                $names[] = $text;
            }
        }

        // 3) JSON-LD (fallback) nếu có keywords
        $jsonLdNodes = $xpath->query("//script[@type='application/ld+json']");
        foreach ($jsonLdNodes as $node) {
            $json = trim((string) $node->textContent);
            if ($json === '') {
                continue;
            }

            $decoded = json_decode($json, true);
            if (! is_array($decoded)) {
                continue;
            }

            $candidates = [];
            $stack = [$decoded];
            while ($stack !== []) {
                $current = array_pop($stack);
                if (! is_array($current)) {
                    continue;
                }

                if (array_key_exists('keywords', $current)) {
                    $candidates[] = $current['keywords'];
                }

                foreach ($current as $value) {
                    if (is_array($value)) {
                        $stack[] = $value;
                    }
                }
            }

            foreach ($candidates as $candidate) {
                if (is_string($candidate)) {
                    $parts = preg_split('/\s*[,;|]\s*/u', $candidate) ?: [];
                    foreach ($parts as $part) {
                        $names[] = $part;
                    }
                } elseif (is_array($candidate)) {
                    foreach ($candidate as $item) {
                        if (is_string($item)) {
                            $names[] = $item;
                        }
                    }
                }
            }
        }

        // normalize + dedupe
        $normalized = [];
        foreach ($names as $name) {
            $name = $this->normalizeKeywordName($name);
            if ($name === '') {
                continue;
            }

            // Lọc keyword rác thường gặp
            $lower = mb_strtolower($name);
            if ($lower === 'dantri' || $lower === 'dân trí' || $lower === 'dân tri') {
                continue;
            }

            $normalized[] = $name;
        }

        $unique = [];
        $seen = [];
        foreach ($normalized as $name) {
            $key = Str::slug($name);
            if ($key === '' || isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $unique[] = $name;
        }

        return $unique;
    }

    protected function normalizeKeywordName(string $name): string
    {
        $name = html_entity_decode($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $name = preg_replace('/\s+/u', ' ', $name) ?? $name;
        $name = trim($name);

        // Một số tag có thể có dấu # hoặc ký tự phân tách
        $name = ltrim($name, "# \t\n\r\0\x0B");
        $name = trim($name, " \t\n\r\0\x0B,;|");

        return $name;
    }

    protected function normalizeUrl(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return $url;
        }

        // protocol-relative: //cdn...
        if (str_starts_with($url, '//')) {
            return 'https:' . $url;
        }

        // absolute already
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        // relative path
        if (str_starts_with($url, '/')) {
            return rtrim($this->baseUrl, '/') . $url;
        }

        // fallback: treat as relative without leading slash
        return rtrim($this->baseUrl, '/') . '/' . ltrim($url, '/');
    }
}
