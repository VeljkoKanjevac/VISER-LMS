<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('section_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();

            $table->string('video_url')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();

            $table->unsignedInteger('sort_order')->default(0);

            $table->boolean('is_free')->default(false);
            $table->boolean('is_published')->default(false);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

            $table->unique(['section_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
