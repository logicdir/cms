<?php

return [
    App\Providers\AppServiceProvider::class,
    
    // CMS Module Providers
    App\Modules\Seo\Providers\SeoServiceProvider::class,
    App\Modules\Security\Providers\SecurityServiceProvider::class,
    App\Modules\Media\Providers\MediaServiceProvider::class,
    App\Modules\User\Providers\UserServiceProvider::class,
    App\Modules\Appearance\Providers\AppearanceServiceProvider::class,
    App\Modules\Installer\Providers\InstallerServiceProvider::class,
];
