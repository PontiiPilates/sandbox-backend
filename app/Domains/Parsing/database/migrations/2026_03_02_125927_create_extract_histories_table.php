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
        Schema::create('extract_histories', function (Blueprint $table) {
            $table->id();

            $table->ulid('extraction_ulid')->comment('идентификатор извлечения');
            $table->dateTime('extraction_date')->comment('дата извлечения');
            $table->char('extraction_type')->comment('тип извлечения');

            $table->integer('count_extraction')->nullable()->comment('количество извлечённых элементов');
            $table->integer('count_saved')->nullable()->comment('количество сохранённых элементов');

            $table->dateTime('prepared_date')->nullable()->comment('дата обработки агентом');
            $table->ulid('prepared_ulid')->nullable()->comment('идентификатор обработки');
<<<<<<< Updated upstream
            $table->ulid('prepared_path')->nullable()->comment('путь до файла с подготовленными мероприятиями');
=======
>>>>>>> Stashed changes

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extract_histories');
    }
};
