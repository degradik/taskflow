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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // int UNSIGNED, PK, AUTO_INCREMENT
            $table->string('name', 50);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'teamlead', 'developer'])->default('developer');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
