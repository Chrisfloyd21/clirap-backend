<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void {
    Schema::create('projects', function (Blueprint $table) {
        $table->id();
        $table->string('title_it');
        $table->text('description_it');
        $table->string('image_url')->nullable();
        $table->string('category'); // ex: Education, Eau, Santé
        $table->boolean('is_completed')->default(false);
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
