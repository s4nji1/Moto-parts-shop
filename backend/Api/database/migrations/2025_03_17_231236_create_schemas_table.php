<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schemas', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('parent_id')->nullable()->constrained('schemas')->onDelete('set null');
            $table->string('version');
            $table->decimal('price', 10, 2)->default(0); // Ajout du prix
            $table->foreignId('moto_id')->nullable()->constrained()->onDelete('set null'); // Ajout de la relation avec les motos
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schemas');
    }
};