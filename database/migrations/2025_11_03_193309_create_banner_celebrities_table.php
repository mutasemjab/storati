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
        Schema::create('banner_celebrities', function (Blueprint $table) {
            $table->id();
            $table->string('photo');
             $table->unsignedBigInteger('celebrity_id')->nullable();
            $table->foreign('celebrity_id')->references('id')->on('celebrities')->onDelete('cascade');
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
        Schema::dropIfExists('banner_celebrities');
    }
};
