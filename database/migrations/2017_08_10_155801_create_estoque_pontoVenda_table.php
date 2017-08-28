<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstoquePontoVendaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoque_pontovenda', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('id_pontovenda')->unsigned();
			$table->integer('id_produto')->unsigned();
			$table->integer('estoque');
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
        Schema::dropIfExists('estoque_pontovenda');
    }
}
