<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pedido;
use App\PontoVenda;
use App\Produto;

class UserGetController extends Controller
{
     public function index(Request $request)
		{
			$request->user()->authorizeRoles(['repositor']);
			$idUser = $request->user()->id;
			//$produtosEstoque = Produto::where('status',1)->sum('quantidade');
			$estoquePdv = PontoVenda::where('ponto_vendas.user_id',$idUser)->join('estoque_pontovenda','ponto_vendas.id','=','estoque_pontovenda.id_pontovenda')->sum('estoque_pontovenda.estoque');
			//$estoquePdv = DB::table('estoque_pontovenda')->join('ponto_vendas','estoque_pontovenda.','=','')->sum('estoque');
			//$produtos->quantidade;
			//$totalProdutos = $produtosEstoque + $produtosVendendo;
			$estoqueRepositor = DB::table('estoque_repositor')->where('id_user', $idUser)->sum('estoque');
			//return view('home',['produtosEstoque' => $produtosEstoque,'produtosVendendo' => $produtosVendendo, 'totalProdutos' => $totalProdutos]);
			return view('repositor.home',['estoqueRepositor' => $estoqueRepositor, 'estoquePdv' => $estoquePdv]);
		}
		
		public function pdv(Request $request)
		{
			$request->user()->authorizeRoles(['repositor']);
			$idUser = $request->user()->id;
			//$produtosEstoque = Produto::where('status',1)->sum('quantidade');
			//$produtosVendendo = DB::table('estoque_pontovenda')->sum('estoque');
			//$produtos->quantidade;
			//$totalProdutos = $produtosEstoque + $produtosVendendo;
			//return view('home',['produtosEstoque' => $produtosEstoque,'produtosVendendo' => $produtosVendendo, 'totalProdutos' => $totalProdutos]);
			$pontosVenda = PontoVenda::where('user_id', $idUser)->join('cidades', 'ponto_vendas.cidade', '=', 'cidades.id')->leftJoin('estoque_pontovenda','ponto_vendas.id','=','estoque_pontovenda.id_pontovenda')->join('status','ponto_vendas.status','=','status.id')->select('ponto_vendas.*','cidades.nome as cidade','estoque_pontovenda.id_pontovenda as pdv',DB::raw('SUM(estoque_pontovenda.estoque) as estoque'),'status.nome as status')->groupBy('ponto_vendas.id')->orderBy('status.id')->get();
			//$pontosVenda = PontoVenda::where('user_id', $idUser)->join('cidades', 'ponto_vendas.cidade', '=', 'cidades.id')->join('status','ponto_vendas.status','=','status.id')->select('ponto_vendas.*','cidades.nome as cidade','status.nome as status')->get();
			$produtos = Produto::join('estoque_repositor','produtos.id','=','estoque_repositor.id_produto')->where('estoque_repositor.id_user', $idUser)->select('produtos.*','estoque_repositor.estoque as estoque')->get();
			return view('repositor.pontosdeVenda', ['id' => $idUser,'pontosvenda' => $pontosVenda,'produtos' => $produtos]);
		}
		
		public function pedidos(Request $request)
		{
			$request->user()->authorizeRoles(['repositor']);
			//$produtos = Produto::all();
			$idUser = $request->user()->id;

			$produtos = Produto::join('estoque_repositor','produtos.id','=','estoque_repositor.id_produto')->where('estoque_repositor.id_user', $idUser)->select('produtos.*','estoque_repositor.estoque as estoque')->get();
			$pontovendas = PontoVenda::where('user_id',$idUser)->where('status',1)->get();
			//$pedidos = Pedido::join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->join('users','pedidos.id_repositor','=','users.id')->select('produtos.nome as produto','produtos.modelo as modelo','pedido_produtos.qtde as qtde','users.name as repositor','pedidos.*')->get();
			//$pedidos = Pedido::join('users','pedidos.id_repositor','=','users.id')->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->select('pedido_produtos.qtde as qtde','pedido_produtos.id_pedido as id_pedido','users.name as repositor','pedidos.*')->groupBy('repositor')->pluck('repositor');;
			$pedidos = Pedido::where('pedidos.id_repositor', $idUser)->join('users','pedidos.id_repositor','=','users.id')->join('ponto_vendas','pedidos.id_pdv','=','ponto_vendas.id')->leftJoin('nf','pedidos.id','=','nf.id_pedido')->select('users.name as repositor','pedidos.*','ponto_vendas.nome as ponto_venda','ponto_vendas.id as id_pontovenda','nf.status as nf')->groupBy('nf.id_pedido')->orderBy('pedidos.id')->get();
			return view('repositor.pedidos',['produtos'=> $produtos, 'pedidos' => $pedidos, 'pontovendas' => $pontovendas]);
		}
		
		public function infopdv(Request $request, $idPdv)
		{
		$request->user()->authorizeRoles(['repositor']);
		//$produtos = Produto::all();
		//$pontovendas = PontoVenda::all();
		$pontoVenda = PontoVenda::find($idPdv);
		//$pedido = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('users','pedidos.id_repositor','=','users.id')->join('ponto_vendas','pedidos.id_pdv','=','ponto_vendas.id')->select('pedido_produtos.qtde as qtde','users.name as repositor','pedidos.*','ponto_vendas.nome as ponto_venda')->first();
		//$produtos = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->select('produtos.nome as nome','produtos.modelo as modelo','produtos.codigo as codigo','pedido_produtos.qtde as qtde','produtos.preco as preco')->get();
		//$pedido = Pedido::find($idPedido);
		
		return view('repositor.pdv.infopdv',[ 'pontoVenda' => $pontoVenda,]);
		}
		
		public function infopedido(Request $request, $idPedido)
		{
		$request->user()->authorizeRoles(['repositor']);
		//$produtos = Produto::all();
		//$pontovendas = PontoVenda::all();
		$pedido = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('users','pedidos.id_repositor','=','users.id')->join('ponto_vendas','pedidos.id_pdv','=','ponto_vendas.id')->select('pedido_produtos.qtde as qtde','users.name as repositor','pedidos.*','ponto_vendas.nome as ponto_venda')->first();
		$produtos = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->select('produtos.nome as nome','produtos.modelo as modelo','produtos.codigo as codigo','pedido_produtos.qtde as qtde','produtos.preco as preco')->get();
		//$pedido = Pedido::find($idPedido);
		
		return view('repositor.pedido.infopedido',[ 'pedido' => $pedido, 'produtos' => $produtos]);
		}
		
		
		
}
