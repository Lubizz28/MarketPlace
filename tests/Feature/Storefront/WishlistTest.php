<?php

namespace Tests\Feature\Storefront;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login_when_toggling_wishlist(): void
    {
        $product = Product::first();

        $response = $this->post(route('wishlist.toggle', $product));

        $response->assertRedirect(route('login'));
    }

    public function test_member_can_toggle_product_in_wishlist(): void
    {
        $member = User::where('email', 'member@medinastyle.com')->first();
        $product = Product::first();

        // 1. Add to wishlist
        $response = $this->actingAs($member)->post(route('wishlist.toggle', $product));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $member->id,
            'product_id' => $product->id,
        ]);

        // 2. Remove from wishlist
        $response2 = $this->actingAs($member)->post(route('wishlist.toggle', $product));
        $response2->assertSessionHas('success');
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $member->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_member_can_view_wishlist_page(): void
    {
        $member = User::where('email', 'member@medinastyle.com')->first();
        $product = Product::first();

        $member->wishlists()->create(['product_id' => $product->id]);

        $response = $this->actingAs($member)->get(route('member.wishlist.index'));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('Wishlist Anda');
    }
}
