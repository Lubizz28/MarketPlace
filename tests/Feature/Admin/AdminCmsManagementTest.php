<?php

namespace Tests\Feature\Admin;

use App\Models\Banner;
use App\Models\Page;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCmsManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_admin_can_manage_hero_banners(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.cms.banners.index'));
        $response->assertStatus(200)->assertSee('CMS Hero Banner');

        // 2. Store
        $storeResponse = $this->actingAs($admin)->post(route('admin.cms.banners.store'), [
            'title' => 'Koleksi Lebaran Spesial',
            'subtitle' => 'Diskon member 15%',
            'image_path' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3',
            'button_text' => 'Beli Sekarang',
            'button_url' => '/catalog',
            'sort_order' => 2,
            'is_active' => '1',
        ]);
        $storeResponse->assertRedirect(route('admin.cms.banners.index'));
        $this->assertDatabaseHas('banners', ['title' => 'Koleksi Lebaran Spesial']);

        // 3. Update
        $banner = Banner::where('title', 'Koleksi Lebaran Spesial')->first();
        $updateResponse = $this->actingAs($admin)->put(route('admin.cms.banners.update', $banner), [
            'title' => 'Koleksi Lebaran Updated',
            'image_path' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3',
            'is_active' => '1',
        ]);
        $updateResponse->assertRedirect(route('admin.cms.banners.index'));
        $this->assertDatabaseHas('banners', ['title' => 'Koleksi Lebaran Updated']);

        // 4. Delete
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.cms.banners.destroy', $banner));
        $deleteResponse->assertRedirect(route('admin.cms.banners.index'));
        $this->assertDatabaseMissing('banners', ['id' => $banner->id]);
    }

    public function test_admin_can_manage_static_pages(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.cms.pages.index'));
        $response->assertStatus(200)->assertSee('CMS Halaman Statis');

        // 2. Store
        $storeResponse = $this->actingAs($admin)->post(route('admin.cms.pages.store'), [
            'title' => 'Panduan Ukuran Gamis Syari',
            'content' => 'Berikut adalah tabel ukuran lingkar dada dan panjang gamis syari.',
            'meta_title' => 'Size Chart Gamis',
            'is_active' => '1',
        ]);
        $storeResponse->assertRedirect(route('admin.cms.pages.index'));
        $this->assertDatabaseHas('pages', ['slug' => 'panduan-ukuran-gamis-syari']);

        // 3. Public access
        $publicResponse = $this->get(route('pages.show', 'panduan-ukuran-gamis-syari'));
        $publicResponse->assertStatus(200)->assertSee('Panduan Ukuran Gamis Syari');
    }

    public function test_admin_can_manage_blog_posts(): void
    {
        $admin = User::where('role', 'admin')->first();

        // 1. Index
        $response = $this->actingAs($admin)->get(route('admin.cms.posts.index'));
        $response->assertStatus(200)->assertSee('CMS Blog & Hijab Styling Guides');

        // 2. Store
        $storeResponse = $this->actingAs($admin)->post(route('admin.cms.posts.store'), [
            'title' => 'Cara Merawat Abaya Sutra Arab',
            'excerpt' => 'Panduan mencuci dan menyetrika kain silk tanpa merusak serat.',
            'body' => 'Gunakan deterjen cair khusus dan hindari memeras abaya terlalu kuat.',
            'is_published' => '1',
        ]);
        $storeResponse->assertRedirect(route('admin.cms.posts.index'));
        $this->assertDatabaseHas('posts', ['title' => 'Cara Merawat Abaya Sutra Arab']);

        $post = Post::where('title', 'Cara Merawat Abaya Sutra Arab')->first();

        // 3. Public Blog List & Detail
        $blogListResponse = $this->get(route('blog.index'));
        $blogListResponse->assertStatus(200)->assertSee('Cara Merawat Abaya Sutra Arab');

        $blogDetailResponse = $this->get(route('blog.show', $post->slug));
        $blogDetailResponse->assertStatus(200)
            ->assertSee('Cara Merawat Abaya Sutra Arab')
            ->assertSee('Gunakan deterjen cair');
    }
}
