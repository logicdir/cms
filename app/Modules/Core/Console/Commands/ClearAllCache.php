<?php

namespace App\Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ClearAllCache extends Command
{
    protected $signature = 'logicdir:cache-clear';
    protected $description = 'Clear all application caches (Bootstrap, Config, Route, View, and Data)';

    public function handle()
    {
        $this->info('Clearing all LogicDir caches...');

        Cache::flush();
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');

        $this->info('Success: All caches cleared.');
    }
}
