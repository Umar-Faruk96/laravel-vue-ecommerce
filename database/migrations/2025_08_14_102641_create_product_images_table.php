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
            $table->string('url', 255);
            $table->string('path', 255)->nullable();
            $table->text('altText')->nullable();
            $table->string('mime', 50);
            $table->integer('size');
            $table->integer('position');
            $table->timestamps();
        });

//        extract data of image, image_mime and image_size columns from products table
        DB::table('product_images')->insert(
            DB::table('products')
                ->selectRaw('id, image, image_mime, image_size')
                ->whereNotNull('image')
                ->get()->toArray()
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
