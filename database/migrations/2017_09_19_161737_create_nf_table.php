<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNfTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nf', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('id_pedido');
			$table->string('xmlAss');
			$table->string('recibo');
			$table->string('protocolo');
			$table->string('xmlPronta');
			$table->string('danfe');
			$table->integer('tipo');
			$table->integer('status');
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
        Schema::dropIfExists('nf');
    }
}
