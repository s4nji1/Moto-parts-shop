<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schema_id')->constrained()->onDelete('cascade');
            $table->integer('quantite');
            $table->decimal('total', 10, 2)->default(0); // Ajout du montant total
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['en_attente', 'confirmee', 'en_cours', 'livree', 'annulee'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commandes');
    }
};