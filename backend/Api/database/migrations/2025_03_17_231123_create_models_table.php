<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('marque');
            $table->year('annee');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('models');
    }
};