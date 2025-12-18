<?php

namespace App\Console\Commands;

use App\Services\DantriCrawler;
use Illuminate\Console\Command;

class CrawlDantri extends Command
{
    protected $signature = 'dantri:crawl';

    protected $description = 'Crawl category và bài viết từ báo Dân trí';

    public function __construct(protected DantriCrawler $crawler)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Bắt đầu crawl Dân trí...');

        $this->crawler->crawlCategoriesAndPosts();

        $this->info('Hoàn thành crawl Dân trí.');

        return self::SUCCESS;
    }
}


