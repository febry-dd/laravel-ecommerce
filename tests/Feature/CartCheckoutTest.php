<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use Database\Seeders\ProductCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_a_product_and_see_the_cart_subtotal(): void
    {
        $product = Product::create([
            'name' => 'Kemeja Linen',
            'slug' => 'kemeja-linen',
            'description' => 'Kemeja katun linen.',
            'price' => 125000,
            'stock' => 4,
        ]);

        $this->post(route('cart.items.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertRedirect(route('cart'));

        $this->get(route('cart'))
            ->assertOk()
            ->assertSee('Kemeja Linen')
            ->assertSee('Rp250.000');
    }

    public function test_customer_can_update_and_remove_a_cart_item(): void
    {
        $product = Product::factory()->create(['price' => 12000, 'stock' => 4]);

        $this->withSession(['cart' => [$product->id => ['quantity' => 1]]])
            ->patch(route('cart.items.update', $product), ['quantity' => 3])
            ->assertRedirect(route('cart'));

        $this->get(route('cart'))->assertSee('Rp36.000');

        $this->delete(route('cart.items.destroy', $product))
            ->assertRedirect(route('cart'));

        $this->get(route('cart'))->assertSee('Keranjang Anda masih kosong.');
    }

    public function test_catalog_searches_filters_and_paginates_products(): void
    {
        Product::factory()->count(13)->sequence(
            ...array_fill(0, 13, ['name' => 'Linen Item', 'category' => 'Home']),
        )->create();
        Product::factory()->create(['name' => 'Linen Shirt', 'category' => 'Fashion']);

        $this->get(route('products.index', ['search' => 'Linen', 'category' => 'Home']))
            ->assertOk()
            ->assertSee('Linen Item')
            ->assertDontSee('Linen Shirt')
            ->assertSee('page=2');

        $this->get(route('products.index', ['search' => 'Linen', 'category' => 'Home', 'page' => 2]))
            ->assertOk()
            ->assertSee('Linen Item');
    }

    public function test_checkout_creates_order_items_and_decrements_stock_atomically(): void
    {
        $product = Product::factory()->create([
            'name' => 'Kemeja Linen',
            'price' => 125000,
            'stock' => 4,
        ]);

        $this->withSession(['cart' => [$product->id => ['quantity' => 2]]])
            ->post(route('checkout.store'), [
                'customer_name' => 'Ayu Putri',
                'customer_email' => 'ayu@example.com',
                'shipping_address' => 'Jl. Melati 10',
                'payment_method' => 'bank_transfer',
            ])
            ->assertRedirect(route('checkout'))
            ->assertSessionMissing('cart');

        $order = Order::query()->with('items')->firstOrFail();
        $this->assertSame('250000.00', $order->total_amount);
        $this->assertSame('pending', $order->status);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame(1, $order->items->count());
        $this->assertSame('Kemeja Linen', $order->items->first()->product_name);
        $this->assertSame(2, $order->items->first()->quantity);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 2]);
    }

    public function test_checkout_rejects_insufficient_stock_without_creating_an_order(): void
    {
        $product = Product::factory()->create(['stock' => 1]);

        $this->withSession(['cart' => [$product->id => ['quantity' => 2]]])
            ->post(route('checkout.store'), [
                'customer_name' => 'Ayu Putri',
                'customer_email' => 'ayu@example.com',
                'shipping_address' => 'Jl. Melati 10',
                'payment_method' => 'cash_on_delivery',
            ])
            ->assertSessionHasErrors('cart')
            ->assertSessionHas('cart');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 1]);
    }

    public function test_product_category_seeder_is_repeatable_and_links_products_to_categories(): void
    {
        $this->seed(ProductCategorySeeder::class);
        $product = Product::query()->where('slug', 'headphone-nirkabel')->firstOrFail();
        $product->decrement('stock');

        $this->seed(ProductCategorySeeder::class);

        $this->assertDatabaseCount('categories', 4);
        $this->assertDatabaseCount('products', 8);
        $this->assertSame(17, $product->fresh()->stock);
        $this->assertNotNull($product->fresh()->categoryRelation);
    }
}
