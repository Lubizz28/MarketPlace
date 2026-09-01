<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SeoController extends Controller
{
    /**
     * Generate dynamic XML Sitemap compliant with sitemaps.org schema.
     */
    public function sitemap(): Response
    {
        $xml = Cache::remember('seo_sitemap_xml', 3600, function () {
            $categories = Category::active()->select('id', 'slug', 'updated_at')->get();
            $brands = Brand::active()->select('id', 'slug', 'updated_at')->get();
            $products = Product::active()
                ->with(['primaryImage'])
                ->select('id', 'slug', 'name', 'updated_at')
                ->latest('updated_at')
                ->get();
            $posts = Post::published()->select('id', 'slug', 'title', 'updated_at')->get();
            $pages = Page::active()->select('id', 'slug', 'title', 'updated_at')->get();

            return view('seo.sitemap', compact('categories', 'brands', 'products', 'posts', 'pages'))->render();
        });

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'X-Robots-Tag' => 'noindex',
        ]);
    }

    /**
     * Generate dynamic robots.txt.
     */
    public function robots(): Response
    {
        $content = Cache::remember('seo_robots_txt', 86400, function () {
            $sitemapUrl = url('/sitemap.xml');
            
            $lines = [
                'User-agent: *',
                'Allow: /',
                'Allow: /catalog',
                'Allow: /category/',
                'Allow: /product/',
                'Allow: /blog',
                'Allow: /blog/',
                'Allow: /pages/',
                '',
                '# Disallow private and transactional routes',
                'Disallow: /admin/',
                'Disallow: /member/',
                'Disallow: /reseller/',
                'Disallow: /cart',
                'Disallow: /checkout',
                'Disallow: /webhook/',
                'Disallow: /login',
                'Disallow: /register',
                '',
                '# Dynamic Sitemap Location',
                "Sitemap: {$sitemapUrl}",
            ];

            return implode("\n", $lines);
        });

        return response($content, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Render PWA Offline fallback page.
     */
    public function offline(): View
    {
        return view('offline');
    }
}
