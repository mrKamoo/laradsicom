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
        Schema::create('prescripteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commune_id')->constrained('communes')->onDelete('cascade');
            $table->string('nom');
            $table->string('prenom');
            $table->string('fonction')->nullable();
            $table->string('service')->nullable();
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('telephone_mobile')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescripteurs');
    }
};
