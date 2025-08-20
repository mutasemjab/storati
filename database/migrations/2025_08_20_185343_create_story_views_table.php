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
        Schema::create('story_views', function (Blueprint $table) {
            $table->id();
               $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('story_id')->constrained('celebrity_stories')->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->timestamps();
            
            // Ensure one view record per user per story
            $table->unique(['user_id', 'story_id']);
            
            // Index for performance
            $table->index(['story_id', 'user_id']);
            $table->index(['user_id', 'viewed_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('story_views');
    }
};
