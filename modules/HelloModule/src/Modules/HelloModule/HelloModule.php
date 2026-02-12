<?php

namespace Modules\HelloModule;

use App\Modules\BaseModule;
use App\Contracts\HookInterface;
use Illuminate\Support\Facades\Log;

class HelloModule extends BaseModule
{
    protected $name = 'Hello World Module';
    protected $slug = 'hello-module';
    protected $version = '1.0.0';

    /**
     * {@inheritdoc}
     */
    public function registerHooks(): void
    {
        /** @var HookInterface $hooks */
        $hooks = app(HookInterface::class);

        // Register a filter to modify the page title
        $hooks->registerFilter('page_title', function($title) {
            return $title . ' | LogicDir CMS';
        });

        // Register an action to log when the module is booted
        $hooks->register('module_booted', function($module) {
            Log::info("Module {$module} has been booted!");
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getMenuItems(): array
    {
        return [
            [
                'title' => 'Hello Module',
                'url' => route('hello.index'),
                'icon' => 'ti ti-hello',
                'permission' => 'view-hello-module'
            ]
        ];
    }
}
