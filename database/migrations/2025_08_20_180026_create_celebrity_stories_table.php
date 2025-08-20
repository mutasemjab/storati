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
        Schema::create('celebrity_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('celebrity_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['photo', 'video']);
            $table->string('media_path'); // Path to photo or video file
            $table->string('thumbnail_path')->nullable(); // For video thumbnails
            $table->text('caption')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable(); // Stories expire after 24 hours
            $table->integer('views_count')->default(0);
            $table->timestamps();
            
            // Index for performance
            $table->index(['celebrity_id', 'is_active', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('celebrity_stories');
    }
};
