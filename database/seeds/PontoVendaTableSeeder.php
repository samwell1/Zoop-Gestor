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
			$pontoVenda->cnpj = '71558829000152';
			$pontoVenda->endereco = 'Sete de Setembro';
			$pontoVenda->numero = '3432';
			$pontoVenda->regiao = 'Centro';
			$pontoVenda->fone = '34320108';
			$pontoVenda->cep = '80230090';
			$pontoVenda->email = 'contato@contafarma.com.br';
			$pontoVenda->estado = 'PR';
			$pontoVenda->cidade = 6015;
			$pontoVenda->user_id = 2;
			$pontoVenda->status = 1;
			$pontoVenda->save();
    }
}

