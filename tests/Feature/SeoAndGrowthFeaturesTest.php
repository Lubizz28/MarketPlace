<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoAndGrowthFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_dynamic_sitemap_xml_can_be_generated_and_is_valid(): void
    {
        $response = $this->get(route('seo.sitemap'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/xml');
        $response->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false);
        $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"', false);
        $response->assertSee(url('/'));
        $response->assertSee(route('catalog'));
        $response->assertSee(route('blog.index'));

        $product = Product::first();
        if ($product) {
            $response->assertSee(route('product.show', $product->slug));
        }

        $category = Category::first();
        if ($category) {
            $response->assertSee(route('category.show', $category->slug));
        }
    }

    public function test_dynamic_robots_txt_can_be_served(): void
    {
        $response = $this->get(route('seo.robots'));

        $response->assertStatus(200);
        $response->assertSee('User-agent: *');
        $response->assertSee('Allow: /catalog');
        $response->assertSee('Allow: /product/');
        $response->assertSee('Disallow: /admin/');
        $response->assertSee('Disallow: /member/');
        $response->assertSee('Disallow: /reseller/');
        $response->assertSee('Disallow: /checkout');
        $response->assertSee('Sitemap: ' . url('/sitemap.xml'));
    }

    public function test_offline_page_renders_successfully(): void
    {
        $response = $this->get(route('offline'));

        $response->assertStatus(200);
        $response->assertSeeText('Mode Offline Sulastika Jaya');
        $response->assertSeeText('Anda Sedang Tidak Terhubung');
    }

    public function test_homepage_contains_seo_pwa_campaign_flash_sale_and_loyalty_presentation(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        // Canonical & SEO
        $response->assertSee('<link rel="canonical" href="' . url('/') . '">', false);
        $response->assertSee('Sulastika Jaya');
        $response->assertSee('summary_large_image');

        // PWA Manifest
        $response->assertSee('manifest.webmanifest');
        $response->assertSee('apple-mobile-web-app-capable');

        // Structured Data Schema
        $response->assertSee('Organization');
        $response->assertSee('WebSite');
        $response->assertSee('SearchAction');

        // Campaign & Flash Sale
        $response->assertSeeText('SULASTIKA');
        $response->assertSeeText('Flash Sale Spesial Hari Ini');
        $response->assertSee('flashTimer');

        // Referral & Loyalty Presentation
        $response->assertSeeText('Raih Komisi Melimpah');
        $response->assertSee('resellerMonthlySales');
        $response->assertSee('calculateCommission');
        $response->assertSee('Tingkatan Keanggotaan');
        $response->assertSeeText('Bronze Member');
        $response->assertSeeText('Platinum VIP');
    }

    public function test_product_detail_page_contains_canonical_og_schemas_flash_timer_and_referral(): void
    {
        $product = Product::first();

        $response = $this->get(route('product.show', $product->slug));

        $response->assertStatus(200);

        // Canonical & OpenGraph
        $response->assertSee('<link rel="canonical" href="' . route('product.show', $product->slug) . '">', false);
        $response->assertSee('property="og:type" content="product"', false);
        $response->assertSee('property="product:price:currency" content="IDR"', false);

        // BreadcrumbList & Product Schema
        $response->assertSee('BreadcrumbList');
        $response->assertSee('Product');
        $response->assertSee('AggregateOffer');

        // Flash Sale & Loyalty Presentation
        $response->assertSeeText('Flash Sale Sulastika');
        $response->assertSeeText('Reward Loyalitas:');
        $response->assertSee('Poin Sulastika');

        // Analytics Hook
        $response->assertSee('viewItem');
    }

    public function test_reseller_views_product_page_with_active_referral_link(): void
    {
        $reseller = User::factory()->create([
            'role' => \App\Enums\UserRole::RESELLER,
        ]);

        $product = Product::first();

        $response = $this->actingAs($reseller)->get(route('product.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSeeText('Link Referral Reseller');
        $response->assertSee('copyReferral');
    }

    public function test_catalog_and_category_pages_have_canonical_and_collection_schema(): void
    {
        $catalogResponse = $this->get(route('catalog'));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('CollectionPage');
        $catalogResponse->assertSee('ItemList');

        $category = Category::first();
        if ($category) {
            $categoryResponse = $this->get(route('category.show', $category->slug));
            $categoryResponse->assertStatus(200);
            $categoryResponse->assertSee('<link rel="canonical" href="' . route('category.show', $category->slug) . '">', false);
            $categoryResponse->assertSee('BreadcrumbList');
        }
    }

    public function test_blog_and_cms_pages_have_structured_data_and_canonical(): void
    {
        $post = Post::published()->first() ?? Post::create([
            'title' => 'Panduan Memilih Gamis Sutra',
            'slug' => 'panduan-memilih-gamis-sutra',
            'body' => 'Gamis sutra jacquard adalah pilihan terbaik untuk acara formal.',
            'excerpt' => 'Tips memilih gamis sutra berkualitas.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $postResponse = $this->get(route('blog.show', $post->slug));
        $postResponse->assertStatus(200);
        $postResponse->assertSee('<link rel="canonical" href="' . route('blog.show', $post->slug) . '">', false);
        $postResponse->assertSee('BlogPosting');
        $postResponse->assertSee('BreadcrumbList');

        $page = Page::active()->first() ?? Page::create([
            'title' => 'Tentang MedinaStyle',
            'slug' => 'tentang-kami',
            'content' => 'MedinaStyle adalah brand busana muslim haute couture.',
            'is_active' => true,
        ]);

        $pageResponse = $this->get(route('pages.show', $page->slug));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('<link rel="canonical" href="' . route('pages.show', $page->slug) . '">', false);
        $pageResponse->assertSee('WebPage');
    }
}
