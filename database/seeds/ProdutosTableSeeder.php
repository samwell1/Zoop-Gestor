<?php

use Illuminate\Database\Seeder;
use App\Produto;

class ProdutosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$produto = new Produto();
		$produto->nome = 'Zoop';
		$produto->modelo = 'Boquinha';
		$produto->codigo = 'zp-bq';
		$produto->quantidade = 500;
		$produto->preco = 5.99;
		$produto->imagem = 'uploads/produtos/Zoop-Boquinha/zp-bq.png';
		$produto->status = 1;
		$produto->save();
    }
}
