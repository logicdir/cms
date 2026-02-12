<?php

namespace App\Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Modules\Seo\Models\SeoMeta;

class WarmCache extends Command
{
    protected $signature = 'logicdir:cache-warm';
    protected $description = 'Warm up the response cache by crawling registered URLs';

    public function handle()
    {
        $this->info('Starting cache warming...');

        // In a real scenario, we'd crawl the sitemap. 
        // For now, let's get URLs from SeoMeta (which tracks our content)
        $urls = SeoMeta::pluck('canonical_url')->filter()->unique();

        if ($urls->isEmpty()) {
            $this->warn('No URLs found to warm up.');
            return;
        }

        $bar = $this->output->createProgressBar(count($urls));
        $bar->start();

        foreach ($urls as $url) {
            try {
                Http::get($url);
            } catch (\Exception $e) {
                $this->error("\nFailed to warm: {$url}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nCache warming completed.");
    }
}
