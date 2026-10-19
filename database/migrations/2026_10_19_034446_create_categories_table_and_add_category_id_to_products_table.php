<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained()->nullOnDelete();
        });

        DB::table('products')
            ->whereNotNull('category')
            ->where('category', '<>', '')
            ->distinct()
            ->pluck('category')
            ->each(function (string $category): void {
                DB::table('categories')->insertOrIgnore([
                    'name' => $category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('products')
                    ->where('category', $category)
                    ->update(['category_id' => DB::table('categories')->where('name', $category)->value('id')]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });

        Schema::dropIfExists('categories');
    }
};
