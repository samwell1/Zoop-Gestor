<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PontoVenda;

class UserPostController extends Controller
{
    public function cadastrarPdv(Request $request)
		{	
			$request->user()->authorizeRoles(['repositor']);
			$idUser = $request->user()->id;
			$pontoVenda = new PontoVenda();
			$pontoVenda->nome = $request['nome'];
			$pontoVenda->cnpj = '77.814.953/0001-18';
			$pontoVenda->endereco = $request['endereco'];
			$pontoVenda->numero = '2150';
			$pontoVenda->cep = '80235090';
			$pontoVenda->fone = '30020482';
			$pontoVenda->email = 'joao@farma.com.br';
			$pontoVenda->regiao = $request['regiao'];
			$pontoVenda->estado = $request['estado'];
			$pontoVenda->cidade = $request['cidade'];
			$pontoVenda->user_id = $idUser;
			$pontoVenda->status = 1;
			$pontoVenda->save();
			
			//DB::table('estoque_pontovenda')->insert(['email' => 'john@example.com', 'votes' => 0]);
			return redirect('user/pdv')->with('status', 'Cadastrado com sucesso!');
		}
}
