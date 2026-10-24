<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_register_without_granting_themselves_admin_access(): void
    {
        $this->post(route('register'), [
            'name' => 'Rina Customer',
            'email' => 'rina@example.com',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'is_admin' => true,
        ])->assertRedirect(route('account.orders.index'));

        $user = User::where('email', 'rina@example.com')->firstOrFail();
        $this->assertFalse($user->is_admin);
        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_authentication_pages_render_the_site_brand(): void
    {
        config(['app.name' => 'Luma Market']);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Luma Market')
            ->assertSee('brand-mark')
            ->assertSee('Masuk ke akunmu');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Luma Market')
            ->assertSee('brand-mark')
            ->assertSee('Buat akun');
    }

    public function test_customer_cannot_access_admin_routes(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_customer_cannot_view_another_customers_order(): void
    {
        $customer = User::factory()->create();
        $otherCustomer = User::factory()->create();
        $order = Order::create([
            'user_id' => $otherCustomer->id,
            'customer_name' => $otherCustomer->name,
            'customer_email' => $otherCustomer->email,
            'shipping_address' => 'Jl. Kenanga 2',
            'total_amount' => 10000,
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);

        $this->actingAs($customer)
            ->get(route('account.orders.show', $order))
            ->assertNotFound();
    }

    public function test_admin_can_create_a_product_with_an_uploaded_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Tas Kanvas',
                'category' => 'Aksesori',
                'description' => 'Tas kanvas harian.',
                'price' => 89000,
                'stock' => 7,
                'image' => UploadedFile::fake()->image('tas.png'),
            ])
            ->assertRedirect();

        $product = Product::where('name', 'Tas Kanvas')->firstOrFail();
        $this->assertSame('Aksesori', $product->category);
        $this->assertSame(7, $product->stock);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_guest_is_redirected_from_admin_and_account_routes(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('account.orders.index'))->assertRedirect(route('login'));
    }

    public function test_admin_cancellation_restores_stock_and_cancelled_orders_cannot_be_reopened(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create(['stock' => 3]);
        $order = Order::create([
            'customer_name' => 'Dewi Customer',
            'customer_email' => 'dewi@example.com',
            'shipping_address' => 'Jl. Mawar 3',
            'total_amount' => 20000,
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => 10000,
            'quantity' => 2,
            'subtotal' => 20000,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.orders.update', $order), [
                'status' => 'cancelled',
                'payment_status' => 'pending',
            ])
            ->assertRedirect(route('admin.orders.show', $order));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 5]);

        $this->put(route('admin.orders.update', $order), [
            'status' => 'processing',
            'payment_status' => 'pending',
        ])->assertSessionHasErrors('status');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock' => 5]);
    }
}
