<?php

use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('status')->insert(['nome' => 'Ativo', 'descricao' => 'Ponto de venda ativado']);
		DB::table('status')->insert(['nome' => 'Pendente', 'descricao' => 'Ponto de venda não ativado']);
		DB::table('status')->insert(['nome' => 'Desativado', 'descricao' => 'Ponto de venda Desativado']);
    }
}
