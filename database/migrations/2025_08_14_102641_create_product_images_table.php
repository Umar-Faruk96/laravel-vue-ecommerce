<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class, 'product_id')->constrained()->cascadeOnDelete();
            $table->string('url', 2000);
            $table->string('path', 255)->nullable();
            $table->text('altText')->nullable();
            $table->string('mime', 50)->nullable();
            $table->integer('size')->nullable();
            $table->integer('position');
            $table->timestamps();
        });

//        extract data of image, image_mime and image_size columns from products table
        DB::table('product_images')->insert(
            DB::table('products')
                ->selectRaw('id, image, image_mime, image_size')
                ->whereNotNull('image')
                ->get()->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'url' => $product->image,
                        'mime' => $product->image_mime,
                        'size' => $product->image_size,
                        'position' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray()
        );

//        remove image, image_mime and image_size columns from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('image_mime');
            $table->dropColumn('image_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        create image, image_mime and image_size columns back in products table
        Schema::table('products', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->after('slug');
            $table->string('image_mime', 50)->nullable()->after('image');
            $table->integer('image_size')->nullable()->after('image_mime');
        });

//        import data of image related columns from product_images table back to products table
        DB::table('products')->update(
            DB::table('product_images')
                ->selectRaw('product_id, url, mime, size')
                ->whereNotNull('url')
                ->get()
                ->map(function ($product) {
                    return [
                        'image' => $product->url,
                        'image_mime' => $product->mime,
                        'image_size' => $product->size,
                    ];
                })
                ->toArray()
        );

        Schema::dropIfExists('product_images');
    }
};
