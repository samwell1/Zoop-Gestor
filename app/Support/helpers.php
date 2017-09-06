<?php
	
	use Illuminate\Support\Facades\DB;
	use App\Produto;
	use App\Pedido;
	use App\PontoVenda;
	use App\User;
	use App\Helpers\HeaderFunctions;
	
	use NFePHP\NFe\Tools;
	use NFePHP\Common\Certificate;
	use NFePHP\Common\Soap\SoapCurl;
	use NFePHP\NFe\Make;
	
	use NFePHP\DA\NFe\Danfe;
	use Illuminate\Support\Facades\Storage;
	
	function set_active($uri)
	{
		return Request::is($uri) ? 'active' : 'inactive';
	}	
	
	function formata_dinheiro($valor) {
		$valor = number_format($valor, 2, ',', '.');
		return "R$ " . $valor;
	}
	
	function formata_valor($valor) {
		$valorFormatado = str_replace(",", ".", $valor);
		return $valorFormatado;
	}
	
	function nfe($idPontoVenda,$aP){
		$nfe = Make::v310();
		
		$nfe->taginfNFe('31110219570803000100550030000006821169793597');
		
		//$nfe->tagide($cUF, $cNF, $natOp, $indPag, $mod, $serie, $nNF, $dhEmi, $dhSaiEnt, $tpNF, $idDest, $cMunFG, $tpImp, $tpEmis, $cDV, $tpAmb, $finNFe, $indFinal, $indPres, $procEmi, $verProc, $dhCont, $xJust);
		
		$nfe->tagide(52,00000010,'Venda de Produto',1,55,1,10,date("Y-m-d\TH:i:sP"),date("Y-m-d\TH:i:sP"),1,1,'5200258',1,1,2,1,1,1,9,0,'4.0.43','','');
		
		$CNPJ = '69.316.888/0001-72';
		$CPF = ''; // Utilizado para CPF na nota
		$xNome = 'ZOOP';
		$xFant = 'ZOOP BR ';
		$IE = '123456';
		$IEST = '';
		$IM = '';
		$CNAE = '';
		$CRT = 1;
		
		$nfe->tagemit($CNPJ, $CPF, $xNome, $xFant, $IE, $IEST, $IM, $CNAE, $CRT);
		
		//endereÃ§o do emitente
		$xLgr = 'Av. Sete de Setembro';
		$nro = '3432';
		$xCpl = 'Qd. 38 Lt. 4,5 e 34';
		$xBairro = 'Rebouçasº';
		$cMun = '4106902';
		$xMun = 'Curitiba';
		$UF = 'PR';
		$CEP = '80230-090';
		$cPais = '1058';
		$xPais = 'Brasil';
		$fone = '34020106';
		$nfe->tagenderEmit($xLgr, $nro, $xCpl, $xBairro, $cMun, $xMun, $UF, $CEP, $cPais, $xPais, $fone);
		
		$pontoVenda = PontoVenda::find($idPontoVenda);
		
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
		$nItem = 1;
		//produtos 2        
		foreach ($aP as $prod) {
			$produto = Produto::find(1);
			$nItem = 1;
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
			$nItem++;
		}
		
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
		$nItem = 2; //produtos 2
		$vDesc = 'Caixa com 1000 unidades';
		$nfe->taginfAdProd($nItem, $vDesc);
		
		//Impostos
		$nItem = 1; //produtos 1
		$vTotTrib = '50.40'; // 226.80 ICMS + 51.50 ICMSST + 50.40 IPI + 39.36 PIS + 81.84 CONFIS
		$nfe->tagimposto($nItem, $vTotTrib);
		
		//ICMS - Imposto sobre CirculaÃ§Ã£o de Mercadorias e ServiÃ§os
		$nItem = 1; //produtos 1
		$orig = '0';
		$cst = '102'; // Tributado Integralmente
		$modBC = '3';
		$pRedBC = '';
		$vBC = '840.00'; // = $qTrib * $vUnTrib
		$pICMS = '27.00'; // AlÃ­quota do Estado de GO p/ 'NCM 2203.00.00 - Cervejas de Malte, inclusive Chope'
		$vICMS = '226.80'; // = $vBC * ( $pICMS / 100 )
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
		$nfe->tagICMSSN($nItem, $orig, $cst, $modBC, $pRedBC, $vBC, $pICMS, $vICMS, $vICMSDeson, $motDesICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pDif);
		
		//ICMS 10
		
		$vST = $vICMSST; // Total de ICMS ST
		
		//ICMSPart - ICMS em OperaÃ§Ãµes Interestaduais - CST 10
		//$resp = $nfe->tagICMSPart($nItem, $orig, $cst, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $pBCOp, $ufST);
		
		//ICMSST - TributaÃ§Ã£o ICMS por SubstituiÃ§Ã£o TributÃ¡ria (ST) - CST 40, 41, 50 e 51
		//$resp = $nfe->tagICMSST($nItem, $orig, $cst, $vBCSTRet, $vICMSSTRet, $vBCSTDest, $vICMSSTDest);
		
		//ICMSSN - TributaÃ§Ã£o ICMS pelo Simples Nacional - CST 30
		//$resp = $nfe->tagICMSSN($nItem, $orig, $csosn, $modBC, $vBC, $pRedBC, $pICMS, $vICMS, $pCredSN, $vCredICMSSN, $modBCST, $pMVAST, $pRedBCST, $vBCST, $pICMSST, $vICMSST, $vBCSTRet, $vICMSSTRet);
		
		//IPI - Imposto sobre Produto Industrializado
		$nItem = 1; //produtos 1
		$cst = '50'; // 50 - SaÃ­da Tributada (CÃ³digo da SituaÃ§Ã£o TributÃ¡ria)
		$clEnq = '';
		$cnpjProd = '';
		$cSelo = '';
		$qSelo = '';
		$cEnq = '999';
		$vBC = '840.00';
		$pIPI = '6.00'; //Calculo por alÃ­quota - 6% AlÃ­quota GO.
		$qUnid = '';
		$vUnid = '';
		$vIPI = '50.40'; // = $vBC * ( $pIPI / 100 )
		$nfe->tagIPI($nItem, $cst, $clEnq, $cnpjProd, $cSelo, $qSelo, $cEnq, $vBC, $pIPI, $qUnid, $vUnid, $vIPI);
		
		//PIS - Programa de IntegraÃ§Ã£o Social
		$nItem = 1; //produtos 1
		$cst = '07'; //OperaÃ§Ã£o TributÃ¡vel (base de cÃ¡lculo = quantidade vendida x alÃ­quota por unidade de produto)
		$vBC = ''; 
		$pPIS = '';
		$vPIS = '39.36';
		$qBCProd = '60.00';
		$vAliqProd = '0.3280';
		$nfe->tagPIS($nItem, $cst, $vBC, $pPIS, $vPIS, $qBCProd, $vAliqProd);
		
		//PISST
		//$resp = $nfe->tagPISST($nItem, $vBC, $pPIS, $qBCProd, $vAliqProd, $vPIS);
		
		//COFINS - ContribuiÃ§Ã£o para o Financiamento da Seguridade Social
		$nItem = 1; //produtos 1
		$cst = '07'; //OperaÃ§Ã£o TributÃ¡vel (base de cÃ¡lculo = quantidade vendida x alÃ­quota por unidade de produto)
		$vBC = '';
		$pCOFINS = '';
		$vCOFINS = '0';
		$qBCProd = '0';
		$vAliqProd = '0';
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
		$vBC = '0';
		$vICMS = '0';
		$vICMSDeson = '0.00';
		$vBCST = '0';
		$vST = '0';
		$vProd = '840.00';
		$vFrete = '0.00';
		$vSeg = '0.00';
		$vDesc = '0.00';
		$vII = '0.00';
		$vIPI = '50.40';
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
		array('4','Pacotes','','','120.000','120.000',''),
		array('2','Volume','','','10.000','10.000','')
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
		$nFat = '000035342';
		$vOrig = '1200.00';
		$vDesc = '';
		$vLiq = '1200.00';
		$nfe->tagfat($nFat, $vOrig, $vDesc, $vLiq);
		
		//dados das duplicatas (Pagamentos)
		$aDup = array(
		array('35342-1','2016-06-20','300.00'),
		array('35342-2','2016-07-20','300.00'),
		array('35342-3','2016-08-20','300.00'),
		array('35342-4','2016-09-20','300.00')
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
		$infCpl = "Pedido Nº16 - {$textoIBPT} ";
		$nfe->taginfAdic($infAdFisco, $infCpl);
		
		$xml = $nfe->getXML();
		
		Storage::put('file.xml', $xml);
		
		if (empty($xml)) {
			//existem falhas
			print_r($nfe->erros);
			
		}	
		$xml = Storage::get('file.xml');
		
		$config= ["atualizacao"=>"2017-09-06 10:10:20",
		"tpAmb"=>2,
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
		
		$xmlAssinada = assinaNfe($xml,$configJson);
		
		$xmlEnviada = enviaNfe($xmlAssinada,$configJson);
		//Imprimir DANFE
		//danfe($xmlAssinada);
		
		
		
	}
	
	function assinaNfe($xml,$configJson)
	{
		
		$content = Storage::get('V3S.pfx');
		$password = '12345678';
		try {
			$tools = new Tools($configJson, Certificate::readPfx($content, $password));
			$xml = $tools->signNFe($xml);
			Storage::put('file.xml', $xml);
			} catch (\Exception $e) {
			//aqui você trata possiveis exceptions
			echo $e->getMessage().'<br>';
		}
		$xml = Storage::get('file.xml');
		return $xml;
	}
	
	function enviaNfe($xml, $configJson)
	{
	
		$content = Storage::get('V3S.pfx');
		$password = '12345678';
		try {
			$tools = new Tools($configJson, Certificate::readPfx($content, $password));
			$xmlEnviada = $tools->sefazEnviaLote(array($xml),'1',0);
			Storage::put('file.xml', $xml);
			} catch (\Exception $e) {
			//aqui você trata possiveis exceptions
			echo $e->getMessage().'<br>';
		}
	
	}
	
	function danfe($xml)
	{
		try {
			$danfe = new Danfe($xml, 'P', 'A4', 'https://wepushbuttons.com.au/wp-content/uploads/2012/03/twitter-logo-small.jpg', 'F', '');
			$id = $danfe->montaDANFE();
			$pdf = $danfe->render();
			//o pdf porde ser exibido como view no browser
			//salvo em arquivo
			//ou setado para download forçado no browser 
			//ou ainda gravado na base de dados
			header('Content-Type: application/pdf');
			echo $pdf;
			} catch (InvalidArgumentException $e) {
			echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
		}    
		
	}	