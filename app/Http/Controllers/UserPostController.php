<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PontoVenda;
use App\Pedido;
use App\Produtos;


class UserPostController extends Controller
{
    public function cadastrarPdv(Request $request)
		{	
			$request->user()->authorizeRoles(['repositor']);
			$idUser = $request->user()->id;
			$pontoVenda = new PontoVenda();
			$pontoVenda->nome = $request['nome'];
			$pontoVenda->cnpj = $request['cnpj'];
			$pontoVenda->endereco = $request['endereco'];
			$pontoVenda->numero = $request['numero'];
			$pontoVenda->cep = $request['cep'];
			$pontoVenda->fone = $request['telefone'];
			$pontoVenda->email = $request['email'];
			$pontoVenda->regiao = $request['regiao'];
			$pontoVenda->estado = $request['estado'];
			$pontoVenda->cidade = $request['cidade'];
			$pontoVenda->user_id = $idUser;
			$pontoVenda->status = 2;
			$pontoVenda->save();
			
			//DB::table('estoque_pontovenda')->insert(['email' => 'john@example.com', 'votes' => 0]);
			return redirect('user/pdv')->with('status', 'Cadastrado com sucesso!');
		}
		
		public function nfepedido(Request $request)
		{
		
		$request->user()->authorizeRoles(['repositor']);
				
		$idPedido = $request['idPedido'];
		
//		
		
		nfe($idPedido);
		//var_dump($pedido);
		//return redirect('user/pedidos')->with('status', 'Nfe gerada com sucesso!');
		
		}
}
