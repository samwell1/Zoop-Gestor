<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
			
			/*$pontoVenda = new PontoVenda();
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
			return redirect('user/pdv')->with('status', 'Cadastrado com sucesso!');*/
			
			//Retira caracteres especiais
			$telMask = array("(","-",")");
			$cnpjMask = array("/","-",".");
			
			$telefone = str_replace($telMask, '', $request['telefone']);
			$cep = str_replace('-', '', $request['cep']);
			$cnpj = str_replace($cnpjMask, '', $request['cnpj']);
			
			$pontoVenda = new PontoVenda();
			$pontoVenda->nome = $request['nome'];
			$pontoVenda->cnpj = $cnpj;
			$pontoVenda->endereco = $request['endereco'];
			$pontoVenda->numero = $request['numero'];
			$pontoVenda->cep = $cep;
			$pontoVenda->fone = $telefone;
			$pontoVenda->email = $request['email'];
			$pontoVenda->regiao = $request['regiao'];
			$pontoVenda->estado = $request['estado'];
			$pontoVenda->cidade = $request['cidade'];
			$pontoVenda->user_id = $idUser;
			$pontoVenda->status = 2;
			
			if($pontoVenda->save())
			{
					//DB::table('estoque_pontovenda')->insert(['id_pontovenda' => 'john@example.com', 'votes' => 0]);
					return redirect('user/pdv')->with('status', 'Cadastrado com sucesso!');
			}
			else
			return redirect('user/pdv')->with('error', 'Erro!');
		}
		
		public function emitNfe(Request $request)
		{
		
			$request->user()->authorizeRoles(['repositor']);
			$idPedido = $request['idPedido'];
				
        		$idPontoVenda = 1;
 				$Produtos[] = array('id' => 1,'qtde' => '1');
 				if(nfe($idPontoVenda,$Produtos,$idPedido))	
				return redirect('user/pedidos')->with('status', 'NF-e gerada com sucesso!');
				else
				return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');
		}
		
		public function enviNfe(Request $request)
		{
		
			$request->user()->authorizeRoles(['repositor']);
			$idPedido = $request['idPedido'];
			$pontoVenda = PontoVenda::find($request['idPdv']);
			$pedido = Pedido::find($idPedido);
		$tpAmb = '2';
		$config= ["atualizacao"=>"2017-09-06 10:10:20",
		"tpAmb"=>$tpAmb,
		"razaosocial"=>"V3S COMERCIO E SERVICOS ADMINISTRATIVOS EIRELI ME",
		"siglaUF"=>"PR",
		"cnpj"=>"26849873000167",
		"ie"=>"9073949480",
		"schemes" => "",
		"versao" => "3.10",
		"tokenIBPT" => "",
		"CSC" => "",
		"CSCid" => "",
		"aProxyConf"=> [
		"proxyIp"=>"",
		"proxyPort"=>"",
		"proxyUser"=>"",
		"proxyPass"=>""
		]
		];
		
 		$configJson = json_encode($config);
 	
		//Array de configuração dos dados do emitente
	    $configJson = json_encode($config);
		//Certificado Digital para emissão das NFe's
		$certificado = Storage::get('V3S.pfx');
		//Senha do Certificado Digital para a emissão das NFe's
		$senha = '12345678';
		
		$nfe = DB::table('nf')->where('id_pedido', $idPedido)->first();
		$nfeAss = Storage::get($nfe->xmlAss);
		
		//danfe($nfeAss);
		
		//Consulta Status do Recibo da XML, caso esteja tudo OK! é retornado o //protocolo para adicionar a XML
		
	/*	$recibo = Storage::get($nfe->recibo);
		
		$protocolo = consultaRec($recibo, $configJson,$certificado,$senha,$tpAmb);

		$urlProtocolo = 'pdvs/'.$pontoVenda->nome.'/nfe/nfe'.strval($pedido->id).'/nfe'.strval($pedido->id).'-Protocolo.xml';
		
	    Storage::put($urlProtocolo, $protocolo);
	
	    $nfe = Storage::get($nfe->xmlAss);

		$protocolo = Storage::get($urlProtocolo);

		$nfe = addProt($nfe, $protocolo, $configJson,$certificado,$senha);
        
		$urlXmlPronta = 'pdvs/'.$pontoVenda->nome.'/nfe/nfe'.strval($pedido->id).'/nfe'.strval($pedido->id).'-nfePronta.xml';
		Storage::put($urlXmlPronta, $nfe);*/
 
   //   $nfePronta = Storage::get($urlXmlPronta);
	  
		header('Content-type: text/xml');
		echo $nfeAss;
		//Imprimir DANFE
	//	danfe($nfePronta);
	
 				/*if()	
				return redirect('user/pedidos')->with('status', 'NF-e enviada com sucesso!');
				else
				return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');*/
		}
		
}
