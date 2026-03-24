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
        Schema::create('extract_tg_events', function (Blueprint $table) {
            $table->id();

            $table->char('peer')->comment('канал');
            $table->bigInteger('peer_id')->comment('идентификатор канала');

            $table->boolean('post')->nullable()->comment('пост/не пост');
            $table->unsignedInteger('post_id')->nullable()->comment('идентификатор поста');
            $table->dateTime('date')->comment('дата публикации поста');
            $table->text('message')->comment('контент поста');

            $table->char('source')->comment('ссылка на источник');

            $table->ulid('ulid')->comment('идентификатор экстракции');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extract_tg_events');
    }
};
