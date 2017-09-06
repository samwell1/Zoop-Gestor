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
			$pontoVenda->cnpj = '71.558.829/0001-52';
			$pontoVenda->endereco = 'Sete de Setembro';
			$pontoVenda->numero = '3432';
			$pontoVenda->regiao = 'Centro';
			$pontoVenda->fone = '3432-0108';
			$pontoVenda->cep = '80230090';
			$pontoVenda->email = 'contato@contafarma.com.br';
			$pontoVenda->estado = 'PR';
			$pontoVenda->cidade = 6015;
			$pontoVenda->user_id = 1;
			$pontoVenda->status = 1;
			$pontoVenda->save();
    }
}

