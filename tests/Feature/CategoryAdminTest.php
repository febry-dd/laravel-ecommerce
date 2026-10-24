<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_update_and_delete_a_category_and_see_its_product_count(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Aksesori']);
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'category' => $category->name,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee('Aksesori')
            ->assertSee('Jumlah produk')
            ->assertSee('1')
            ->assertSee(route('admin.products.index'));

        $this->post(route('admin.categories.store'), ['name' => 'Pakaian'])
            ->assertRedirect();
        $newCategory = Category::where('name', 'Pakaian')->firstOrFail();
        $this->assertModelExists($newCategory);

        $this->put(route('admin.categories.update', $category), ['name' => 'Aksesori Rumah'])
            ->assertRedirect(route('admin.categories.edit', $category));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Aksesori Rumah']);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'category' => 'Aksesori Rumah']);

        $this->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => null,
            'category' => null,
        ]);
    }

    public function test_admin_product_list_shows_product_details_and_supports_category_assignment_and_crud(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Peralatan']);

        $this->actingAs($admin)
            ->post(route('admin.products.store'), [
                'name' => 'Botol Minum',
                'description' => 'Botol baja tahan karat.',
                'category_id' => $category->id,
                'price' => 75000,
                'stock' => 12,
                'image' => UploadedFile::fake()->image('botol.png'),
            ])
            ->assertRedirect();

        $product = Product::where('name', 'Botol Minum')->firstOrFail();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'category_id' => $category->id,
            'category' => 'Peralatan',
        ]);
        Storage::disk('public')->assertExists($product->image);

        $this->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('ID')
            ->assertSee('Gambar')
            ->assertSee('Nama')
            ->assertSee('Deskripsi')
            ->assertSee('Stok')
            ->assertSee('Harga')
            ->assertSee('Botol Minum')
            ->assertSee('Botol baja tahan karat.')
            ->assertSee('Gambar Botol Minum')
            ->assertSee(basename($product->image))
            ->assertSee('Peralatan')
            ->assertSee(route('admin.categories.index'));

        $this->get(route('admin.products.show', $product))
            ->assertOk()
            ->assertSee('Peralatan');

        $this->put(route('admin.products.update', $product), [
            'name' => 'Botol Minum Baja',
            'description' => 'Botol tahan karat ukuran besar.',
            'category_id' => '',
            'price' => 85000,
            'stock' => 8,
        ])->assertRedirect(route('admin.products.edit', $product));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Botol Minum Baja',
            'category_id' => null,
            'category' => null,
        ]);

        $this->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_customer_cannot_access_category_management(): void
    {
        $customer = User::factory()->create();

        $this->actingAs($customer)
            ->get(route('admin.categories.index'))
            ->assertForbidden();
    }
}
