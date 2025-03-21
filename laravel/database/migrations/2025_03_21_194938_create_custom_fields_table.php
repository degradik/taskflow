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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('title');             // Название поля
            $table->string('slug')->unique();    // Слаг для обращения
            $table->string('type');              // Тип поля: text, number, date, select
            $table->string('entity_type');       // К какой сущности относится (например, Task)
            $table->json('options')->nullable(); // Дополнительные опции (например, список значений для select)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
