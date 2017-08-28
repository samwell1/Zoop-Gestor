<?php
	
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