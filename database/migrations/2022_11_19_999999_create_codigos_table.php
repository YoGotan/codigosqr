<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('codigos', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_spanish2_ci';
            $table->id();
            $table->foreignId('id_cliente')->references('id')->on('clientes')->nullable();
            $table->string('imagen')->nullable();
            $table->text('token')->unique();
            $table->foreignId('id_local')->references('id')->on('locales');
            $table->foreignId('id_rango')->references('id')->on('rangos_cupon');
            $table->boolean('usado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codigos');
    }
};
