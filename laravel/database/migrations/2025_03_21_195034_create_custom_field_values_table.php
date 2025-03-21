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
        Schema::create('custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_field_id')->constrained('custom_fields')->onDelete('cascade');
            $table->unsignedBigInteger('entity_id'); // id сущности (Task id, Project id и т.д.)
            $table->string('entity_type');           // Тип сущности (Task, Project и т.п.)
            $table->text('value')->nullable();       // Значение кастомного поля
            $table->timestamps();
        
            $table->index(['entity_id', 'entity_type']); // Для быстрого поиска
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_field_values');
    }
};
