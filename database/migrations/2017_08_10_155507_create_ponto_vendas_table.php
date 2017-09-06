<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreatePontoVendasTable extends Migration
	{
		/**
			* Run the migrations.
			*
			* @return void
		*/
		public function up()
		{
			Schema::create('ponto_vendas', function (Blueprint $table) {
				$table->increments('id');
				$table->string('nome');
				$table->string('cnpj');
				$table->string('endereco');
				$table->string('numero');
				$table->string('regiao');
				$table->string('cep');
				$table->string('fone');
				$table->string('email');
				$table->string('estado');
				$table->integer('cidade');
				$table->string('ie')->nullable();
				$table->string('isuf')->nullable();
				$table->string('im')->nullable();
				$table->integer('user_id');
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
			Schema::dropIfExists('ponto_vendas');
		}
	}
