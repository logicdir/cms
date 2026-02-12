# SEO Module Integration Guide

## Adding SEO to Content Models

Add the `HasSeoMeta` trait to any model that needs SEO:

```php
use App\Modules\Seo\Traits\HasSeoMeta;

class Post extends Model
{
    use HasSeoMeta;
    
    // ... rest of model
}
```

This automatically:
- Adds `seoMeta()` relationship
- Tracks slug changes
- Creates automatic 301 redirects

## Using SEO Services

### Meta Management

```php
use App\Modules\Seo\Services\SeoMetaService;

$seoService = app(SeoMetaService::class);

// Get meta for content
$meta = $seoService->getMeta($post);

// Set custom meta
$seoService->setMeta($post, [
    'meta_title' => 'Custom Title',
    'meta_description' => 'Custom description',
    'og_image' => 'https://example.com/image.jpg'
]);
```

### Structured Data

```php
use App\Modules\Seo\Services\StructuredDataService;

$structuredData = app(StructuredDataService::class);

// Generate Article schema
$schema = $structuredData->generateArticle($post);
echo $structuredData->toJsonLd($schema);
```

## Frontend Integration

### In Inertia Pages

```vue
<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    seo: Object
});
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>
        <meta name="description" :content="seo.description" />
        <meta property="og:title" :content="seo.og_title" />
        <meta property="og:description" :content="seo.og_description" />
        <meta property="og:image" :content="seo.og_image" />
    </Head>
</template>
```

### Using SEO Components

```vue
<script setup>
import SeoMetaManager from '@/Components/Seo/SeoMetaManager.vue';
import SocialPreview from '@/Components/Seo/SocialPreview.vue';

const seoMeta = ref({});
</script>

<template>
    <SeoMetaManager 
        v-model="seoMeta" 
        :content-title="post.title" 
    />
    
    <SocialPreview 
        :meta="seoMeta"
        :content-title="post.title"
        :content-description="post.excerpt"
        :content-image="post.featured_image"
    />
</template>
```

## Middleware Setup

Add to `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... other middleware
        \App\Modules\Seo\Http\Middleware\RedirectMiddleware::class,
        \App\Modules\Seo\Http\Middleware\SeoMiddleware::class,
    ],
];
```

## Performance Optimization

### .htaccess Configuration

Copy the example .htaccess rules from `app/Modules/Seo/.htaccess.example` to your `public/.htaccess` file.

### Resource Hints

```php
use App\Modules\Seo\Services\PerformanceService;

$performance = app(PerformanceService::class);
$hints = $performance->generateResourceHints();

// In your layout:
foreach ($hints['preconnect'] as $url) {
    echo "<link rel='preconnect' href='$url' />";
}
```

## Sitemaps

Sitemaps are automatically available at:
- `/sitemap.xml` - Index
- `/sitemap-posts.xml` - Posts
- `/sitemap-pages.xml` - Pages
- `/sitemap-images.xml` - Images

Clear sitemap cache after content changes:

```php
app(\App\Modules\Seo\Services\SitemapService::class)->clearCache();
```
