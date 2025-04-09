<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('schemas', function (Blueprint $table) {
            $table->string('image')->nullable()->after('moto_id');
            $table->string('serial_number')->nullable()->after('image');
        });
    }

    public function down()
    {
        Schema::table('schemas', function (Blueprint $table) {
            $table->dropColumn(['image', 'serial_number']);
        });
    }
};