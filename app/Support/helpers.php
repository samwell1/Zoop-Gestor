<?php
	
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Storage;
	
	use App\Produto;
	use App\Pedido;
	use App\PontoVenda;
	use App\User;
	use App\Helpers\HeaderFunctions;
	
	use NFePHP\NFe\Tools;
	use NFePHP\NFe\Complements;
	use NFePHP\Common\Certificate;
	use NFePHP\Common\Soap\SoapCurl;
	use NFePHP\NFe\Make;
	use NFePHP\DA\NFe\Danfe;
	
	
	function set_active($uri)
	{
		return Request::is($uri) ? 'active' : 'inactive';
	}	
	
	function formata_dinheiro($valor) {
		$valor = number_format($valor, 2, ',', '.');
		return "R$ " . $valor;
	}
	
	function formata_numero($valor) {
		$valor = number_format($valor, 2, '.', '');
		return $valor;
	}
	
	function formata_valor($valor) {
		$valorFormatado = str_replace(",", ".", $valor);
		return $valorFormatado;
	}
	
	function apiIugu($metodo,$dados,$url){
	$usuario = '16f5eb7c2cb167556af5aceabdddcbd5:'; //API Token Homologação
	$headers[] = "Authorization: Basic " . base64_encode($usuario . ":");
    $headers[] = "Accept: application/json";
    $headers[] = "Accept-Charset: utf-8";
    $headers[] = "User-Agent: Iugu PHPLibrary";
    $headers[] = "Accept-Language: pt-br;q=0.9,pt-BR";
	
		$cr = curl_init(); 
		curl_setopt($cr, CURLOPT_URL, $url); 
		curl_setopt($cr, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($cr, CURLOPT_HTTPHEADER, $headers);
		if($metodo == 'POST' || $metodo == 'post'){
		curl_setopt($cr, CURLOPT_POST,1);
		curl_setopt($cr, CURLOPT_POSTFIELDS, http_build_query($dados));
		}
		curl_setopt($cr, CURLOPT_SSL_VERIFYHOST, 2);
    	curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($cr, CURLOPT_CAINFO, Storage::get('certificado/ca-bundle.crt'));
		//executando recurso 
		$retorno = curl_exec($cr);
		//fechando-o para liberação do sistema. 
		curl_close($cr); 
		//Retornando retorno
		return $retorno;
		
		/*	Exemplo de manipulação do retorno
		$array = json_decode($retorno);
		foreach ($array->logs as $logs)
		echo 'Status: '.$logs->description.' - Data: '.$logs->created_at.'<br>';
		echo $array->id; // Pegar ID para guardar no banco
		*/
		
	}
	
	function nfe($idPontoVenda,$aP,$idPedido,$tipoNFe){
 	$nfe = Make::v310();
 	//Ambiente de Emissão: PRODUÇÃO = 1; HOMOLOGAÇÃO = 2;
		$tpAmb = '2';
		$nfe->taginfNFe('31110219570803000100550030000006821169793597');
		
		$pedido = Pedido::where('pedidos.id', $idPedido)->join('pedido_produtos', 'pedidos.id','=','pedido_produtos.id_pedido')->join('ponto_vendas','pedidos.id_pdv','=','ponto_vendas.id')->select('pedido_produtos.qtde as qtde','pedidos.*','ponto_vendas.id as id_pontovenda')->first();
		
    	$produtos = DB::table('pedido_produtos')->where('pedido_produtos.id_pedido', $idPedido)->join('produtos', 'pedido_produtos.id_produto','=','produtos.id')->select('produtos.nome as nome','produtos.modelo as modelo','produtos.codigo as codigo','pedido_produtos.qtde as qtde','produtos.id as id_produto','produtos.preco as preco','pedido_produtos.id_produto as id_prod_ped','produtos.peso as peso')->get();
		
		$pontoVenda = PontoVenda::find($pedido->id_pontovenda);
		
		$lastNFe = DB::table('nf')->select('id')->orderBy('id','desc')->first();
		if(count($lastNFe) == 0 || count($lastNFe) == null)
		$nNF = 1;
		else
		$nNF = ($lastNFe->id + 1);
		
		$referenciarNfe = false;
	switch($tipoNFe){
			    //envioRemessaPR
			    case 1:
			     $CFOPp = '5917';   
				 $natOp = 'Remessa de mercadoria em consignação mercantil ou industrial';
				 $tpNF = 1;
				 $finNFe = 1;
			    break;
			    //envioRemessaOut
			    case 2:
			     $CFOPp = '6917';   
				 $natOp = 'Remessa de mercadoria em consignação mercantil ou industrial';
				 $tpNF = 1;
				 $finNFe = 1;
			    break;
			    //retornoRemessaPR
			    case 3:
			     $CFOPp = '1918';   
				 $natOp = 'Devolução de mercadoria remetida em consignação mercantil ou industrial';
				 $tpNF = 0;
				 $finNFe = 4;
				 $referenciarNfe = true;
			    break;
			    //retornoRemessaOut
			    case 4:
			     $CFOPp = '2918';   
				 $natOp = 'Devolução de mercadoria remetida em consignação mercantil ou industrial';
				 $tpNF = 0;
				 $finNFe = 4;
				 $referenciarNfe = true;
			    break;
			    //vendaPR
			    case 5:
			     $CFOPp = '5102';   
				 $natOp = 'Venda de mercadoria adquirida ou recebida de terceiros';
				 $tpNF = 1;
				 $finNFe = 1;
			    break;
			    //vendaOut
			    case 6:
			     $CFOPp = '6102';   
				 $natOp = 'Venda de mercadoria adquirida ou recebida de terceiros';
				 $tpNF = 1;
				 $finNFe = 1;
			    break;
			}

		//$nfe->tagide($cUF, $cNF, $natOp, $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt, $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV, $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont, $xJust);
		
		$nfe->tagide(41,00000010,$natOp,1,55,1,(600+$nNF),date("Y-m-d\TH:i:sP"),date("Y-m-d\TH:i:sP"),$tpNF,1,'4106902',1,1,2,$tpAmb,$finNFe,1,9,0,'4.0.43','','');
		
		
	//refNFe NFe referenciada  
	if($referenciarNfe == true){
	$lastPedido = Pedido::where('id_pdv',$pontoVenda->id)->select('id')->orderBy('id','desc')->skip(1)->take(1)->first();
	$lastNFe = DB::table('nf')->where('tipo',1)->where('id_pedido',$lastPedido->id)->select('xmlPronta')->orderBy('id','desc')->first();
	$xmlPronta = Storage::get($lastNFe->xmlPronta);
	$dom = new DOMDocument;
    $dom->loadXML($xmlPronta);
	
	$chave = $dom->getElementsByTagName('chNFe')->item(0)->nodeValue;
	$refNFe = $chave;
	
	$nfe->tagrefNFe($refNFe);	
	}


		$CNPJ = '26849873000167';
		$CPF = ''; // Utilizado para CPF na nota
		$xNome = 'ZOOP';
		$xFant = 'ZOOP BR ';
		$IE = '9073949480';
		$IEST = '';
		$IM = '';
		$CNAE = '';
		$CRT = 1;
		
		$nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);
		
		//endereÃ§o do emitente
		$xLgr = 'RUA BRASILIO ITIBERE';
		$nro = '2023';
		$xCpl = 'AP 102';
		$xBairro = 'REBOUCAS';
		$cMun = '4106902';
		$xMun = 'Curitiba';
		$UF = 'PR';
		$CEP = '80230050';
		$cPais = '1058';
		$xPais = 'BRASIL';
		$fone = '34020106';
		$nfe->tagenderEmit($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
		
		
		
		//destinatÃ¡rio
		$CNPJ = $pontoVenda->cnpj;
		$CPF = '';
		$idEstrangeiro = '';
		$xNome = $pontoVenda->nome;
		$indIEDest = '1';
		$IE = $pontoVenda->ie;
		$ISUF = $pontoVenda->isuf;
		$IM = $pontoVenda->im;
		$email = $pontoVenda->email;
		$resp = $nfe->tagdest($CNPJ, $CPF, $idEstrangeiro, $xNome, $indIEDest, $IE, $ISUF, $IM, $email);
		
		
		
		//EndereÃ§o do destinatÃ¡rio
		$cidade = DB::table('cidades')->find($pontoVenda->cidade);
		
		$xLgr = $pontoVenda->endereco;
		$nro = $pontoVenda->numero;
		$xCpl = '';
		$xBairro = $pontoVenda->regiao;
		$cMun = '4106902';
		$xMun = $cidade->nome;
		$UF = $pontoVenda->estado;
		$CEP = $pontoVenda->cep;
		$cPais = '1058';
		$xPais = 'Brasil';
		$fone = $pontoVenda->fone;
		$resp = $nfe->tagenderDest($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
		
		//produtos 1 (Limite da API Ã© de 56 itens por Nota)
		/*$aP[] = array(
			'nItem' => 1,
			'cProd' => '15',
			'cEAN' => '97899072659522',
			'xProd' => 'Zoop Variados',
			'NCM' => '22030000',
			'EXTIPI' => '',
			'CFOP' => '5101',
			'uCom' => 'Un',
			'qCom' => '4',
			'vUnCom' => '210.00',
			'vProd' => '840.00',
			'cEANTrib' => '',
			'uTrib' => 'Lt',
			'qTrib' => '120',
			'vUnTrib' => '7.00',
			'vFrete' => '',
			'vSeg' => '',
			'vDesc' => '',
			'vOutro' => '',
			'indTot' => '1',
			'xPed' => '16',
			'nItemPed' => '1',
		'nFCI' => '');*/
		$sItem = 1;
		$vTotalPedido = 0;
		$qTotalPedido = 0;
		$pesoTotalPedido = 0;
		$qComq = '0';
		foreach ($produtos as $prod) {
			$produto = Produto::find($prod->id_produto);
			$nItem = $sItem;
			$cProd = $produto->codigo;
			$cEAN = '7895099315424';
			$xProd = $produto->nome.' - '.$produto->modelo;
			$NCM = '39249000';
			//$NCM = '22030000';
			$EXTIPI = '';
			$CFOP = $CFOPp;
			$uCom = 'Un';
			if($tipoNFe == 1 || $tipoNFe == 2){
		//Envio
		$qComq = strval($pontoVenda->max_estoque);
		$vProdq = number_format(($pontoVenda->max_estoque * $produto->preco), 2 ,'.','');//'41.90';
		$qTribq = strval($pontoVenda->max_estoque);
		}else if($tipoNFe == 3 || $tipoNFe == 4){
		//Retorno
		$qComq = strval($pontoVenda->max_estoque - $prod->qtde);
		$vProdq = number_format((($pontoVenda->max_estoque - $prod->qtde) * $produto->preco), 2 ,'.','');//'41.90';
		$qTribq = strval($pontoVenda->max_estoque - $prod->qtde);
		}else if($tipoNFe == 5 || $tipoNFe == 6){
		//Venda
		$qComq = strval($prod->qtde);
		$vProdq = number_format(($prod->qtde * $produto->preco), 2 ,'.','');//'41.90';
		$qTribq = strval($prod->qtde);
		}
			$qCom = $qComq;//'10';
			$vUnCom = number_format($produto->preco, 2 ,'.','');//'4.19';
			$vProd = $vProdq;
			$cEANTrib = '';
			$uTrib = 'Un';
			$qTrib = $qTribq;
			$vUnTrib = number_format($produto->preco, 2 ,'.','');//'4.19';
			$vFrete = '';
			$vSeg = '';
			$vDesc = '';
			$vOutro = '';
			$indTot = '1';
			$xPed = strval($pedido->id);
			$nItemPed = strval($prod->id_prod_ped);
			$nFCI = '';
			
			$resp = $nfe->tagprod($nItem, $cProd, $cEAN, $xProd, $NCM, $EXTIPI, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $nFCI);

		
		/*foreach ($aP as $prod) {
			$produto = Produto::find(1);
			$nItem = $nItem++;
			$cProd = $produto->codigo;
			$cEAN = '97899072659522';
			$xProd = $produto->nome.' - '.$produto->modelo;
			$NCM = '22030000';
			$EXTIPI = '';
			$CFOP = '5101';
			$uCom = 'Un';
			$qCom = $prod['qtde'];
			$vUnCom = strval($produto->preco);
			$vProd = strval($prod['qtde'] * $produto->preco);
			$cEANTrib = '';
			$uTrib = 'Lt';
			$qTrib = '120';
			$vUnTrib = '7.00';
			$vFrete = '';
			$vSeg = '';
			$vDesc = '';
			$vOutro = '';
			$indTot = '1';
			$xPed = '15';
			$nItemPed = strval($nItem);
			$nFCI = '';
			$resp = $nfe->tagprod($nItem, $cProd, $cEAN, $xProd, $NCM, $EXTIPI, $CFOP, $uCom, $qCom, $vUnCom, $vProd, $cEANTrib, $uTrib, $qTrib, $vUnTrib, $vFrete, $vSeg, $vDesc, $vOutro, $indTot, $xPed, $nItemPed, $nFCI);
		}*/
		
		// InformaÃ§Ãµes adicionais na linha do Produto
		/*$nItem = 1; //produtos 1
			$vDesc = 'Barril 30 Litros Chopp Tipo Pilsen - Pedido NÂº15';
		$resp = $nfe->taginfAdProd($nItem, $vDesc);*/
		
		//$nItem = $sItem; //produtos 2
		//$vDesc = 'Caixa com 1000 unidades';
		//$nfe->taginfAdProd($nItem, $vDesc);
		
		//Impostos
		$nItem = $sItem; //produtos 1
		$vTotTrib = '0.00'; // 226.80 ICMS + 51.50 ICMSST + 50.40 IPI + 39.36 PIS + 81.84 CONFIS
		$nfe->tagimposto($nItem, $vTotTrib);
		
		//ICMS - Imposto sobre Circulação de Mercadorias e Serviços
        $nItem = $sItem; //produtos 1
        $orig = '0';
        //$cst = '00'; // Tributado Integralmente
        $csosn = '102'; // Tributado Integralmente
        $modBC = '3';
        $pRedBC = '';
        $vBC = '0.00'; // = $qTrib * $vUnTrib
        $pICMS = '0.00'; // Alíquota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
        $vICMS = '0.00'; // = $vBC * ( $pICMS / 100 )
        $pCredSN = '0';
        $vCredICMSSN ='0';
        $vICMSDeson = '';
        $motDesICMS = '';
        $modBCST = '';
        $pMVAST = '';
        $pRedBCST = '';
        $vBCST = '';
        $pICMSST = '';
        $vICMSST = '';
        $pDif = '';
        $vICMSDif = '';
        $vICMSOp = '';
        $vBCSTRet = '';
        $vICMSSTRet = '';
		
		//ICMSSN - TributaÃ§Ã£o ICMS pelo Simples Nacional - CST 30
		$nfe->tagICMSSN($nItem, $orig, $csosn, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $pCredSN, $vCredICMSSN, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $vBCSTRet, $vICMSSTRet);
		
		
	//	$nfe->tagICMSSN($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif);
		
		
		
		//ICMS 10
		
		$vST = $vICMSST; // Total de ICMS ST
		
		//ICMSPart - ICMS em OperaÃ§Ãµes Interestaduais - CST 10
		//$resp = $nfe->tagICMSPart($nItem, $orig, $cst, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pBCOp, $ufST);
		
		//ICMSST - TributaÃ§Ã£o ICMS por SubstituiÃ§Ã£o TributÃ¡ria (ST) - CST 40, 41, 50 e 51
		//$resp = $nfe->tagICMSST($nItem, $orig, $cst, $vBCSTRet, $vICMSSTRet, $vBCSTDest, $vICMSSTDest);
		

		//IPI - Imposto sobre Produto Industrializado
		$nItem = $sItem; //produtos 1
		$cst = '99'; // 50 - SaÃ­da Tributada (CÃ³digo da SituaÃ§Ã£o TributÃ¡ria)
		$clEnq = '';
		$cnpjProd = '';
		$cSelo = '';
		$qSelo = '';
		$cEnq = '999';
		$vBC = '0.00';
		$pIPI = '0.00'; //Calculo por alÃ­quota - 6% AlÃ­quota GO.
		$qUnid = '';
		$vUnid = '';
		$vIPI = '0.00'; // = $vBC * ( $pIPI / 100 )
		$nfe->tagIPI($nItem, $cst, $clEnq, $cnpjProd, $cSelo, $qSelo, $cEnq, $vBC, $pIPI, $qUnid, $vUnid, $vIPI);
		
		//PIS - Programa de IntegraÃ§Ã£o Social
		$nItem = $sItem; //produtos 1
		$cst = '99'; //OperaÃ§Ã£o TributÃ¡vel (base de cÃ¡lculo = quantidade vendida x alÃ­quota por unidade de produto)
		$vBC = ''; 
		$pPIS = '';
		$vPIS = '0.00';
		$qBCProd = '0.00';
		$vAliqProd = '0.0000';
		$nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);
		
		//PISST
		//$resp = $nfe->tagPISST($nItem, $vBC, $pPIS, $qBCProd, $vAliqProd, $vPIS);
		
		//COFINS - ContribuiÃ§Ã£o para o Financiamento da Seguridade Social
		$nItem = $sItem; //produtos 1
		$cst = '99'; //OperaÃ§Ã£o TributÃ¡vel (base de cÃ¡lculo = quantidade vendida x alÃ­quota por unidade de produto)
		$vBC = '';
		$pCOFINS = '';
		$vCOFINS = '0.00';
		$qBCProd = '0.00';
		$vAliqProd = '0.000';
		$nfe->tagCOFINS($nItem, $cst, $vBC, $pCOFINS, $vCOFINS, $qBCProd, $vAliqProd);
		
		//COFINSST
		//$resp = $nfe->tagCOFINSST($nItem, $vBC, $pCOFINS, $qBCProd, $vAliqProd, $vCOFINS);
		
		//II
		//$resp = $nfe->tagII($nItem, $vBC, $vDespAdu, $vII, $vIOF);
		
		//ICMSTot
		//$resp = $nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);
		
		//ISSQNTot
		//$resp = $nfe->tagISSQNTot($vServ, $vBC, $vISS, $vPIS, $vCOFINS, $dCompet, $vDeducao, $vOutro, $vDescIncond, $vDescCond, $vISSRet, $cRegTrib);
		
		//retTrib
		//$resp = $nfe->tagretTrib($vRetPIS, $vRetCOFINS, $vRetCSLL, $vBCIRRF, $vIRRF, $vBCRetPrev, $vRetPrev);
		$vTotalPedido += $vProdq;
		$qTotalPedido += $qComq;
		$pesoTotalPedido += $produto->peso;
		$sItem++;
		}
		
		//InicializaÃ§Ã£o de vÃ¡riaveis nÃ£o declaradas...
		$vII = isset($vII) ? $vII : 0;
		$vIPI = isset($vIPI) ? $vIPI : 0;
		$vIOF = isset($vIOF) ? $vIOF : 0;
		$vPIS = isset($vPIS) ? $vPIS : 0;
		$vCOFINS = isset($vCOFINS) ? $vCOFINS : 0;
		$vICMS = isset($vICMS) ? $vICMS : 0;
		$vBCST = isset($vBCST) ? $vBCST : 0;
		$vST = isset($vST) ? $vST : 0;
		$vISS = isset($vISS) ? $vISS : 0;
		
		//total
		$vBC = '0.00';
		$vICMS = '0';
		$vICMSDeson = '0.00';
		$vBCST = '0';
		$vST = '0';
		$vProd = number_format($vTotalPedido, 2 ,'.','');//'41.90';
		$vFrete = '0.00';
		$vSeg = '0.00';
		$vDesc = '0.00';
		$vII = '0.00';
		$vIPI = '0.00';
		$vPIS = '0';
		$vCOFINS = '0';
		$vOutro = '0.00';
		$vNF = number_format($vProd-$vDesc-$vICMSDeson+$vST+$vFrete+$vSeg+$vOutro+$vII+$vIPI, 2, '.', '');
		$vTotTrib = number_format($vICMS+$vST+$vII+$vIPI+$vPIS+$vCOFINS+$vIOF+$vISS, 2, '.', '');
		$nfe->tagICMSTot($vBC, $vICMS, $vICMSDeson, $vBCST, $vST, $vProd, $vFrete, $vSeg, $vDesc, $vII, $vIPI, $vPIS, $vCOFINS, $vOutro, $vNF, $vTotTrib);
		
		//frete
		$modFrete = '0'; //0=Por conta do emitente; 1=Por conta do destinatÃ¡rio/remetente; 2=Por conta de terceiros; 9=Sem Frete;
		$nfe->tagtransp($modFrete);
		
		//transportadora
		//$CNPJ = '';
		//$CPF = '12345678901';
		//$xNome = 'Ze da Carroca';
		//$IE = '';
		//$xEnder = 'Beco Escuro';
		//$xMun = 'Campinas';
		//$UF = 'SP';
		//$resp = $nfe->tagtransporta($CNPJ, $CPF, $xNome, $IE, $xEnder, $xMun, $UF);
		
		//valores retidos para transporte
		//$vServ = '258,69'; //Valor do ServiÃ§o
		//$vBCRet = '258,69'; //BC da RetenÃ§Ã£o do ICMS
		//$pICMSRet = '10,00'; //AlÃ­quota da RetenÃ§Ã£o
		//$vICMSRet = '25,87'; //Valor do ICMS Retido
		//$CFOP = '5352';
		//$cMunFG = '3509502'; //CÃ³digo do municÃ­pio de ocorrÃªncia do fato gerador do ICMS do transporte
		//$resp = $nfe->tagretTransp($vServ, $vBCRet, $pICMSRet, $vICMSRet, $CFOP, $cMunFG);
		
		//dados dos veiculos de transporte
		//$placa = 'AAA1212';
		//$UF = 'SP';
		//$RNTC = '12345678';
		//$resp = $nfe->tagveicTransp($placa, $UF, $RNTC);
		
		//dados dos reboques
		//$aReboque = array(
		//    array('ZZQ9999', 'SP', '', '', ''),
		//    array('QZQ2323', 'SP', '', '', '')
		//);
		//foreach ($aReboque as $reb) {
		//    $placa = $reb[0];
		//    $UF = $reb[1];
		//    $RNTC = $reb[2];
		//    $vagao = $reb[3];
		//    $balsa = $reb[4];
		//    //$resp = $nfe->tagreboque($placa, $UF, $RNTC, $vagao, $balsa);
		//}
		
		//Dados dos Volumes Transportados
		$aVol = array(
		array($qTotalPedido,'Unidades','','',intval(number_format($pesoTotalPedido, 2 ,'.','')),intval(number_format($pesoTotalPedido, 2 ,'.','')),'')
		);
		foreach ($aVol as $vol) {
			$qVol = $vol[0]; //Quantidade de volumes transportados
			$esp = $vol[1]; //EspÃ©cie dos volumes transportados
			$marca = $vol[2]; //Marca dos volumes transportados
			$nVol = $vol[3]; //NumeraÃ§Ã£o dos volume
			$pesoL = intval($vol[4]); //Kg do tipo Int, mesmo que no manual diz que pode ter 3 digitos verificador...
			$pesoB = intval($vol[5]); //...se colocar Float nÃ£o vai passar na expressÃ£o regular do Schema. =\
			$aLacres = $vol[6];
			$nfe->tagvol($qVol, $esp, $marca, $nVol, $pesoL, $pesoB, $aLacres);
		}
		
		//dados da fatura
		$nFat = $pedido->id;
		$vOrig = number_format($vTotalPedido, 2 ,'.','');
		$vDesc = '';
		$vLiq = number_format($vTotalPedido, 2 ,'.','');
		$nfe->tagfat($nFat, $vOrig, $vDesc, $vLiq);
		
		//dados das duplicatas (Pagamentos)
		$aDup = array(

		array($pedido->id,date('Y-m-d', strtotime('+5 days')),number_format($vTotalPedido, 2 ,'.',''))
		);
		foreach ($aDup as $dup) {
			$nDup = $dup[0]; //CÃ³digo da Duplicata
			$dVenc = $dup[1]; //Vencimento
			$vDup = $dup[2]; // Valor
			$nfe->tagdup($nDup, $dVenc, $vDup);
		}
		
		
		//*************************************************************
		//Grupo obrigatÃ³rio para a NFC-e. NÃ£o informar para a NF-e.
		//$tPag = '03'; //01=Dinheiro 02=Cheque 03=CartÃ£o de CrÃ©dito 04=CartÃ£o de DÃ©bito 05=CrÃ©dito Loja 10=Vale AlimentaÃ§Ã£o 11=Vale RefeiÃ§Ã£o 12=Vale Presente 13=Vale CombustÃ­vel 99=Outros
		//$vPag = '1452,33';
		//$resp = $nfe->tagpag($tPag, $vPag);
		
		//se a operaÃ§Ã£o for com cartÃ£o de crÃ©dito essa informaÃ§Ã£o Ã© obrigatÃ³ria
		//$CNPJ = '31551765000143'; //CNPJ da operadora de cartÃ£o
		//$tBand = '01'; //01=Visa 02=Mastercard 03=American Express 04=Sorocred 99=Outros
		//$cAut = 'AB254FC79001'; //nÃºmero da autorizaÃ§Ã£o da tranzaÃ§Ã£o
		//$resp = $nfe->tagcard($CNPJ, $tBand, $cAut);
		//**************************************************************
		
		// Calculo de carga tributÃ¡ria similar ao IBPT - Lei 12.741/12
		$federal = number_format($vII+$vIPI+$vIOF+$vPIS+$vCOFINS, 2, ',', '.');
		$estadual = number_format($vICMS+$vST, 2, ',', '.');
		$municipal = number_format($vISS, 2, ',', '.');
		$totalT = number_format($federal+$estadual+$municipal, 2, ',', '.');
		$textoIBPT = "Valor Aprox. Tributos R$ {$totalT} - {$federal} Federal, {$estadual} Estadual e {$municipal} Municipal.";
		
		//InformaÃ§Ãµes Adicionais
		//$infAdFisco = "SAIDA COM SUSPENSAO DO IPI CONFORME ART 29 DA LEI 10.637";
		$infAdFisco = "";
		$infCpl = "Pedido Nº".$pedido->id." - {$textoIBPT} ";
		$nfe->taginfAdic($infAdFisco, $infCpl);
		
		$xml = $nfe->getXML();
		
		
		
		if (empty($xml)) {
			//existem falhas
			print_r($nfe->erros);
			
		}	
		
		
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
		$PastaNF = '';
		$tipoNFeDB = 0;
		if($tipoNFe == 1 || $tipoNFe == 2){
		//Envio
		$PastaNFe = '/envioRemessa';
		$tipoNFeDB = 1;
		}else if($tipoNFe == 3 || $tipoNFe == 4){
		//Retorno
		$PastaNFe = '/retornoRemessa';
		$tipoNFeDB = 2;
		}else if($tipoNFe == 5 || $tipoNFe == 6){
		//Venda
		$PastaNFe = '/Venda';
		$tipoNFeDB = 3;
		}
		//Assina XML (NFe)
		$xmlAssinada = assinaNfe($xml,$configJson,$certificado,$senha);
		$urlXmlAssinada = 'pdvs/'.$pontoVenda->nome.'/nfe/nfe'.strval($pedido->id).$PastaNFe.'/nfe'.strval($pedido->id).'-Assinada.xml';
		Storage::put($urlXmlAssinada, $xmlAssinada);
		
        //Envia XML (NFe) para a SEFAZ e retorna o Status do XML
		$recibo = enviaNfe($xmlAssinada,$configJson,$certificado,$senha,$tpAmb);
		$urlRecibo = 'pdvs/'.$pontoVenda->nome.'/nfe/nfe'.strval($pedido->id).$PastaNFe.'/nfe'.strval($pedido->id).'-Recibo.xml';
		Storage::put($urlRecibo, $recibo);
		
	//	danfe($xmlAssinada);
		
	//	print_r($recibo);
		
		
			//if(DB::table('nf')->insert(['id_pedido' => $pedido->id , 'status' => 1, 'xmlAss' => $urlXmlAssinada,'tipo' => $tipoNFeDB]))
			if(DB::table('nf')->insert(['id_pedido' => $pedido->id , 'status' => 1, 'xmlAss' => $urlXmlAssinada,'recibo' => $urlRecibo,'tipo' => $tipoNFeDB]))
			return true;
		else
			return false;
        
      
		//Consulta Status do Recibo da XML, caso esteja tudo OK! é retornado o //protocolo para adicionar a XML
		
	/*	$recibo = Storage::get('recibo.xml');
		
		$protocolo = consultaRec($recibo, $configJson,$certificado,$senha,$tpAmb);
	    print_r($protocolo);
	     Storage::put('protocolo.xml', $protocolo);
	
	    $nfe = Storage::get('nfe/'.$pontoVenda->nome.'/nfe.xml');

	   $protocolo = Storage::get('protocolo.xml');

	   $nfe = addProt($nfe, $protocolo, $configJson,$certificado,$senha);
        
       Storage::put('nfePronta.xml', $nfe);*/
 
   //    $nfePronta = Storage::get('nfePronta.xml');
	  
		//Imprimir DANFE
	//	danfe($nfePronta);
		
		//  $xmlValida = validaNfe($nfe,$configJson,$certificado,$senha);

	}
	
	function assinaNfe($xml,$configJson,$certificado,$senha)
	{
		try {
			$tools = new Tools($configJson, Certificate::readPfx($certificado, $senha));
			$xmlAssinada = $tools->signNFe($xml);
		//	Storage::put('nfe.xml', $xml);
		
		    return $xmlAssinada;
		    
			} catch (\Exception $e) {
			//aqui você trata possiveis exceptions
			echo $e->getMessage().'<br>';
		}
		
	}
	
	function enviaNfe($xml, $configJson,$certificado,$senha,$tpAmb)
	{
		try {
			$tools = new Tools($configJson, Certificate::readPfx($certificado, $senha));
			$retorno = $tools->sefazEnviaLote(array($xml),$tpAmb,0);
			
            return $retorno;
			} catch (\Exception $e) {
			//aqui você trata possiveis exceptions
			echo $e->getMessage().'<br>';
		}
		
		
	}
	
	function consultaRec($retornoEnvio, $configJson, $certificado, $senha,$tpAmb)
	{
		try {
			$tools = new Tools($configJson, Certificate::readPfx($certificado, $senha));
        $dom = new DOMDocument;
        $dom->loadXML($retornoEnvio);

        $recibo = $dom->getElementsByTagName('infRec')->item(0)->nodeValue;
        //Retira o ultimo digito do recibo, já que são apenas 15 numeros
        $reciboAr = substr($recibo, 0, -1);
        
        $retorno  = $tools->sefazConsultaRecibo($reciboAr, $tpAmb);
        
        return $retorno;
	    } catch (InvalidArgumentException $e) {
	    echo "Ocorreu um erro durante a consulta :" . $e->getMessage();
	    }
	    
	 }
	
	function addProt($nfe, $protocolo, $configJson, $certificado, $senha)
	{
	    try {
			$tools = new Tools($configJson, Certificate::readPfx($certificado, $senha));

		     $xmlPronta = Complements::toAuthorize($nfe, $protocolo);
            return $xmlPronta;
		} catch (\Exception $e) {
			//aqui você trata possiveis exceptions
			echo $e->getMessage().'<br>';
		}
    
	}
	
	function validaNfe($nfe, $configJson, $certificado, $senha)
	{
	    try {
			$tools = new Tools($configJson, Certificate::readPfx($certificado, $senha));
			$retorno = $tools->sefazValidate($nfe);

            return $retorno;
			} catch (\Exception $e) {
			//aqui você trata possiveis exceptions
			echo $e->getMessage().'<br>';
		}
	}
	
	function danfe($xml)
	{
		try {
			$danfe = new Danfe($xml, 'P', 'A4', '', 'S', '');
    $id = $danfe->montaDANFE();
    $pdf = $danfe->render();
	
	return $pdf;
    //o pdf porde ser exibido como view no browser
    //salvo em arquivo
    //ou setado para download forçado no browser 
    //ou ainda gravado na base de dados
    //header('Content-Type: application/pdf');
    //echo $pdf;
			} catch (InvalidArgumentException $e) {
			echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
		}    
		
	}