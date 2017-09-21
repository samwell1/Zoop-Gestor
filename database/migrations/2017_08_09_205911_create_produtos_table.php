<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
			$table->string('nome');
			$table->string('modelo');
			$table->string('imagem');
			$table->integer('quantidade');
			$table->float('preco');
			$table->float('peso');
            $table->string('codigo')->unique();
			$table->string('cean')->nullable();
			$table->string('ncm')->nullable();
			$table->string('extipi')->nullable();
			$table->string('cfop')->nullable();
			$table->string('ceantrib')->nullable();
			$table->string('utrib')->nullable();
			$table->string('qtrib')->nullable();
			$table->string('vuntrib')->nullable();
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
        Schema::dropIfExists('produtos');
    }
}
