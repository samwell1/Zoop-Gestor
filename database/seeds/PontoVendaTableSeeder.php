<?php

use Illuminate\Database\Seeder;
use App\PontoVenda;
class PontoVendaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
			$pontoVenda = new PontoVenda();
			$pontoVenda->nome = 'ContaFarma';
			$pontoVenda->endereco = 'Sete de Setembro';
			$pontoVenda->regiao = 'Central';
			$pontoVenda->estado = 'PR';
			$pontoVenda->cidade = 6015;
			$pontoVenda->status = 1;
			$pontoVenda->save();
    }
}
