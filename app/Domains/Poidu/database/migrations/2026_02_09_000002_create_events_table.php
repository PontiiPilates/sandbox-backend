<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained();

            $table->char('title');
            $table->text('description');

            $table->dateTime('date_time')->nullable();

            $table->integer('price_min');
            $table->integer('price_max');

            $table->char('channel');
            $table->bigInteger('channel_id');
            $table->unsignedInteger('post_id');
            $table->char('link_to_post');
            $table->dateTime('post_was_created');

            $table->boolean('human')->default(false)->comment('t - созданный человеком, f - полученный путём парсинга');
            $table->boolean('approved')->default(false)->comment('t - администратор одобрил для публикации, f - не модерирован');

            $table->text('prompt')->nullable()->comment('промпт для генерации');
            $table->char('preview')->nullable()->comment('имя сгенерированного изображения');

            $table->integer('views')->default(0)->comment('количество просмотров');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
