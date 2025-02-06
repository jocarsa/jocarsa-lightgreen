<?php
	/*
		Funciones encargada de codificar y decodificar mediante base64
	*/
	function codifica($cadena){
		for($i = 0;$i<6;$i++){
			$cadena = base64_encode($cadena);
		}
		return $cadena;
	}
	
	function decodifica($cadena){
		for($i = 0;$i<6;$i++){
			$cadena = base64_decode($cadena);
		}
		return $cadena;
	}
?>
