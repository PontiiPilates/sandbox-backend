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
        Schema::create('pipeline_event_minings', function (Blueprint $table) {
            $table->id();

            $table->dateTime('1_parsing')->nullable()->comment('извлечение данных из telegram');
            $table->dateTime('2_classify')->nullable()->comment('классификация поста');
            $table->dateTime('3_classify_update')->nullable()->comment('обновление классифицированными данными');
            $table->dateTime('4_beautify')->nullable()->comment('создание промпта, заголовка, описания');
            $table->dateTime('5_beautify_update')->nullable()->comment('обновление промптом, заголовком, описанием');
            $table->dateTime('6_imagenize')->nullable()->comment('иллюстрация контента');

            $table->text('failed')->nullable()->comment('причина остановки пайплайна');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pipeline_event_minings');
    }
};
