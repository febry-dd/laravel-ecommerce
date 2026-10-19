<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Headphone Nirkabel', 'slug' => 'headphone-nirkabel', 'category' => 'Elektronik', 'price' => 349000, 'stock' => 18, 'description' => 'Headphone ringan dengan koneksi Bluetooth dan suara jernih.'],
            ['name' => 'Lampu Meja Minimalis', 'slug' => 'lampu-meja-minimalis', 'category' => 'Rumah', 'price' => 185000, 'stock' => 12, 'description' => 'Lampu meja dengan cahaya hangat untuk ruang kerja dan kamar.'],
            ['name' => 'Botol Minum Stainless', 'slug' => 'botol-minum-stainless', 'category' => 'Aksesori', 'price' => 99000, 'stock' => 30, 'description' => 'Botol minum tahan lama untuk menemani aktivitas harian.'],
            ['name' => 'Tas Kanvas Harian', 'slug' => 'tas-kanvas-harian', 'category' => 'Fashion', 'price' => 149000, 'stock' => 15, 'description' => 'Tas kanvas serbaguna dengan ruang utama yang lapang.'],
            ['name' => 'Keyboard Mekanis', 'slug' => 'keyboard-mekanis', 'category' => 'Elektronik', 'price' => 525000, 'stock' => 8, 'description' => 'Keyboard mekanis nyaman untuk bekerja maupun bermain.'],
            ['name' => 'Organizer Kayu', 'slug' => 'organizer-kayu', 'category' => 'Rumah', 'price' => 129000, 'stock' => 20, 'description' => 'Organizer meja untuk menjaga perlengkapan tetap rapi.'],
            ['name' => 'Dompet Kartu Kulit', 'slug' => 'dompet-kartu-kulit', 'category' => 'Aksesori', 'price' => 115000, 'stock' => 11, 'description' => 'Dompet ringkas dengan beberapa slot kartu praktis.'],
            ['name' => 'Kemeja Katun Santai', 'slug' => 'kemeja-katun-santai', 'category' => 'Fashion', 'price' => 239000, 'stock' => 9, 'description' => 'Kemeja katun nyaman untuk tampilan santai sehari-hari.'],
        ];

        foreach ($products as $productData) {
            $category = Category::query()->firstOrCreate(['name' => $productData['category']]);

            Product::query()->firstOrCreate(
                ['slug' => $productData['slug']],
                [
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'category_id' => $category->id,
                    'category' => $category->name,
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'image' => null,
                ],
            );
        }
    }
}
