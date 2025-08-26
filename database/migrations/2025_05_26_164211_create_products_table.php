<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar');
            $table->text('description_ar');
            $table->text('description_en');
            $table->double('price');
            $table->double('tax')->default(16);
            $table->double('discount_percentage')->nullable();
            $table->double('price_after_discount')->nullable();
            $table->enum('gender', ['man', 'woman', 'both'])->default('both');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('celebrity_id')->nullable();
            $table->foreign('celebrity_id')->references('id')->on('celebrities')->onDelete('cascade');
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');

            $table->tinyInteger('my_collabs')->default(1); // 1 yes //2 no
            $table->tinyInteger('is_featured')->default(1); // 1 yes //2 no
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
