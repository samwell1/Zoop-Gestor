<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
			//$produtosEstoque = Produto::where('status',1)->sum('quantidade');
			//$produtosVendendo = DB::table('estoque_pontovenda')->sum('estoque');
			//$produtos->quantidade;
			//$totalProdutos = $produtosEstoque + $produtosVendendo;
			//return view('home',['produtosEstoque' => $produtosEstoque,'produtosVendendo' => $produtosVendendo, 'totalProdutos' => $totalProdutos]);
			return view('repositor.home');
		}
}
