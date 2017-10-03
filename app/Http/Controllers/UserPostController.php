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
			$pontoVenda->max_estoque = $request['estoque'];
			$pontoVenda->user_id = $idUser;
			$pontoVenda->status = 2;
			$pontoVenda->save();
			
			//Retira caracteres especiais
			/*$telMask = array("(","-",")");
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
			$pontoVenda->user_id = $idUser*/
			
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
			
        		
 				$Produtos[] = array('id' => 1,'qtde' => '1');
 			/*	if(nfe($idPontoVenda,$Produtos,$idPedido))	
				return redirect('user/pedidos')->with('status', 'NF-e gerada com sucesso!');
				else
				return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');*/
			
			$lastNFe = DB::table('nf')->where('id_pdv', $request['idPdv'])->select('id')->orderBy('id','desc')->first();
			
		
			
			$pontoVenda = PontoVenda::find($request['idPdv']);
			$idPontoVenda = $pontoVenda->id;
			
			if($pontoVenda->estado == 16){
			if(count($lastNFe) == 0 || count($lastNFe) == null){		//Verifica se já existe NFe
			if(nfe($idPontoVenda,$Produtos,$idPedido,1)){	//Envia Remessa PR
						return redirect('user/pedidos')->with('status', 'NF-e gerada com sucesso!');
						}else{
						return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');
						}
			}else if(nfe($idPontoVenda,$Produtos,$idPedido,5)){	//Venda Produto PR
					if(nfe($idPontoVenda,$Produtos,$idPedido,3)){	//Retorno Remessa PR
					if(nfe($idPontoVenda,$Produtos,$idPedido,1)){	//Envia Remessa PR
						return redirect('user/pedidos')->with('status', 'NF-e gerada com sucesso!');
						}
					}
				}
				else
				return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');
			}else{
				if(count($lastNFe) == 0 || count($lastNFe) == null){		//Verifica se já existe NFe
			if(nfe($idPontoVenda,$Produtos,$idPedido,2)){	//Envia Remessa  Fora do PR
						return redirect('user/pedidos')->with('status', 'NF-e gerada com sucesso!');
						}else{
						return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');
						}
			
			if(nfe($idPontoVenda,$Produtos,$idPedido,6)){	//Venda Produto Fora do PR
					if(nfe($idPontoVenda,$Produtos,$idPedido,4)){	//Retorno Remessa  Fora do PR
					if(nfe($idPontoVenda,$Produtos,$idPedido,2)){	//Envia Remessa  Fora do PR
						return redirect('user/pedidos')->with('status', 'NF-e gerada com sucesso!');
						}
					}
				}
				else
				return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');
			}
				
		}
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
		//Array de configuração dos dados do emitente
	    $configJson = json_encode($config);
		//Certificado Digital para emissão das NFe's
		$certificado = Storage::get('V3S.pfx');
		//Senha do Certificado Digital para a emissão das NFe's
		$senha = '12345678';
		
		$nfes = DB::table('nf')->where('id_pedido', $idPedido)->get();
		
		foreach($nfes as $nfe){
			
		if($nfe->tipo == 1){
		//Envio
		$PastaNFe = '/envioRemessa';
		
		}else if($nfe->tipo == 2){
		//Retorno
		$PastaNFe = '/retornoRemessa';
		
		}else if($nfe->tipo == 3){
		//Venda
		$PastaNFe = '/Venda';
		
		}
		
		$nfeAss = Storage::get($nfe->xmlAss);
		
		//danfe($nfeAss);
		
		//Consulta Status do Recibo da XML, caso esteja tudo OK! é retornado o //protocolo para adicionar a XML
		
		$recibo = Storage::get($nfe->recibo);
		
		$protocolo = consultaRec($recibo, $configJson,$certificado,$senha,$tpAmb);
		$urlProtocolo = 'pdvs/'.$pontoVenda->nome.'/nfe/nfe'.strval($pedido->id).$PastaNFe.'/nfe'.strval($pedido->id).'-Protocolo.xml';
		
		if(DB::table('nf')->where('id_pedido',$idPedido)->where('tipo', $nfe->tipo)->update(['status' => 2, 'protocolo' => $urlProtocolo]))
			Storage::put($urlProtocolo, $protocolo);
	
	    $nf = Storage::get($nfe->xmlAss);
		$protocolo = Storage::get($urlProtocolo);

		$nf = addProt($nf, $protocolo, $configJson,$certificado,$senha);
        
		$urlXmlPronta = 'pdvs/'.$pontoVenda->nome.'/nfe/nfe'.strval($pedido->id).$PastaNFe.'/nfe'.strval($pedido->id).'-nfePronta.xml';
		if(DB::table('nf')->where('id_pedido',$idPedido)->where('tipo', $nfe->tipo)->update(['status' => 3, 'xmlPronta' => $urlXmlPronta]))
		Storage::put($urlXmlPronta, $nf);
 
		$nfePronta = Storage::get($urlXmlPronta);
	  
		$sucesso = false;
		$pdf = danfe($nfePronta);
		$urlDanfe = 'pdvs/'.$pontoVenda->nome.'/nfe/nfe'.strval($pedido->id).$PastaNFe.'/nfe'.strval($pedido->id).'-Danfe.pdf';
		if(DB::table('nf')->where('id_pedido',$idPedido)->where('tipo', $nfe->tipo)->update(['status' => 3, 'danfe' => $urlDanfe])){
			Storage::put($urlDanfe, $pdf);
			$sucesso = true;
		}
}
			
			if($sucesso == true)	
				return redirect('user/pedidos')->with('status', 'NF-e enviada com sucesso!');
				else
				return redirect('user/pedidos')->with('error', 'Erro! NF-e com erro.');
		}
		
		public function emitBoleto(Request $request)
		{
			$pdv = PontoVenda::find($request['idPdv']);
			$pedido = Pedido::find($request['idPedido']);
			$items[] = '';
			$arrayItem = "";
			
			$produtos = DB::table('pedido_produtos')->where('pedido_produtos.id_pedido', $request['idPedido'])->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->select('produtos.nome as nome','produtos.modelo as modelo','produtos.codigo as codigo','pedido_produtos.qtde as qtde','produtos.preco as preco')->get();
			
			foreach($produtos as $produto)
			$items[] = ['name' => $produto->nome.' - '.$produto->modelo, 'description' => $produto->nome.' - '.$produto->modelo, 'quantity' => $produto->qtde, 'price_cents' =>  $produto->preco * 100];
			
			foreach($items as $item)
			$arrayItem = $item;
			
			$dadosFatura = Array('email' => $pdv->email, 'due_date'=> strval(date('Y-m-d', strtotime('+3 days'))),
			'payer' => Array(	'cpf_cnpj' => $pdv->cnpj,'name' => $pdv->nome,
			'phone_prefix' => '', 'phone' => $pdv->fone,'email' => $pdv->email,
			'address' => Array('zip_code' => $pdv->cep, 'city' => $pdv->cidades, 'state' => $pdv->estado, 
			'street' => $pdv->endereco,'number' => $pdv->numero, 'district' => $pdv->regiao, 'country' => 'Brasil', 
			'complement' => 'Comercial')),
			'payable_with' => 'bank_slip', 
			'items[]' => $arrayItem);
			
			$urlFatura =  "http://api.iugu.com/v1/invoices/";
			
			$iuguApi = apiIugu('POST', $dadosFatura, $urlFatura);
			
			$retorno = json_decode($iuguApi);
			$idBoleto = $retorno->id;
			
			$pedido->boleto = $idBoleto;
			
			if($pedido->save())
				return redirect('user/pedidos')->with('status', 'Boleto emitido e enviado com sucesso!');
			else
				return redirect('user/pedidos')->with('error', 'Erro!');
			
			
		}
		
		public function user_editar_perfil(Request $request){
			$request->user()->authorizeRoles(['repositor']);
			$usuario = User::find($request['idUser']);
			
			if (Hash::check ( $request['confirmpassword'], $usuario->password )) {
			
			$usuario->name = $request['nome'];
			$usuario->email = $request['email'];
			
			if($request['password'] != null || $request['password'] != '')
			$usuario->password = bcrypt($request['password']);
		
			$usuario->save();
			return redirect('user/perfil')->with('status', 'Perfil editado com sucesso!');
			}else{
			return redirect('user/perfil')->with('error', 'Senha atual incorreta!');
			}
			
		}
		
}
