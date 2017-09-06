<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PontoVenda;
use App\Produto;

class UserGetController extends Controller
{
     public function index(Request $request)
		{
			$request->user()->authorizeRoles(['repositor']);
			//$produtosEstoque = Produto::where('status',1)->sum('quantidade');
			//$produtosVendendo = DB::table('estoque_pontovenda')->sum('estoque');
			//$produtos->quantidade;
			//$totalProdutos = $produtosEstoque + $produtosVendendo;
			//return view('home',['produtosEstoque' => $produtosEstoque,'produtosVendendo' => $produtosVendendo, 'totalProdutos' => $totalProdutos]);
			return view('repositor.home');
		}
		
		public function pdv(Request $request)
		{
			$request->user()->authorizeRoles(['repositor']);
			$id = $request->user()->id;
			//$produtosEstoque = Produto::where('status',1)->sum('quantidade');
			//$produtosVendendo = DB::table('estoque_pontovenda')->sum('estoque');
			//$produtos->quantidade;
			//$totalProdutos = $produtosEstoque + $produtosVendendo;
			//return view('home',['produtosEstoque' => $produtosEstoque,'produtosVendendo' => $produtosVendendo, 'totalProdutos' => $totalProdutos]);
			$pontosVenda = PontoVenda::where('user_id', $id)->where('status',1)->join('cidades', 'ponto_vendas.cidade', '=', 'cidades.id')->select('ponto_vendas.*','cidades.nome as cidade')->get();
			$produtos = Produto::all();
			return view('repositor.pontosdeVenda', ['id' => $id,'pontosvenda' => $pontosVenda,'produtos' => $produtos]);
		}
}
