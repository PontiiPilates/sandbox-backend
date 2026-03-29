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

            $table->dateTime('parsing')->nullable()->comment('извлечение данных из telegram');
            $table->dateTime('details')->nullable()->comment('извлечение деталей поста');
            $table->dateTime('update_details')->nullable()->comment('обновление новыми деталями');
            $table->dateTime('prompt')->nullable()->comment('создание промпта для генерации preview');
            $table->dateTime('update_prompt')->nullable()->comment('создание промпта для генерации preview');
            $table->dateTime('preview')->nullable()->comment('генерация preview');

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
