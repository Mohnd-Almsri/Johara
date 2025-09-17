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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('main_description');
            $table->text('second_description');
            $table->text('third_description');
            $table->string('location');
            $table->string('date');
            $table->json('details')->nullable();
            $table->string('contractor');
            $table->string('mainImage')->nullable();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
