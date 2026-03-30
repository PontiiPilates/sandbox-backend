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
        Schema::create('event_minings', function (Blueprint $table) {
            $table->id();

            // колонки для сырых данных
            $table->char('peer')->comment('канал');
            $table->bigInteger('peer_id')->comment('идентификатор канала');
            $table->boolean('post')->nullable()->comment('пост/не пост');
            $table->unsignedInteger('post_id')->nullable()->comment('идентификатор поста');
            $table->integer('date')->comment('дата публикации поста');
            $table->text('message')->comment('контент поста');

            // колонки для обработанных данных
            $table->foreignId('category_id')->nullable()->constrained();
            $table->foreignId('additional_category_id')->nullable()->constrained(table: 'categories', indexName: 'id');
            $table->char('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('date_time')->nullable();
            $table->integer('price_min')->default(0);
            $table->integer('price_max')->default(0);
            $table->text('prompt')->nullable()->comment('промпт для генерации preview');
            $table->char('preview')->nullable()->comment('имя сгенерированного изображения');

            // общие колонки
            $table->char('source')->nullable()->comment('источник данных');
            $table->boolean('approved')->default(1)->comment('одобрено для публикации');
            $table->integer('views')->default(0)->comment('количество просмотров');

            // метаданные
            $table->char('source_file')->nullable()->comment('источник данных');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_minings');
    }
};
