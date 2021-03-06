<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../services/LinhaService.php');
include('../services/ParadaService.php');
include('../services/TaxiService.php');

//Linha
const ID_LINHA = "il";
const NOME_CODIGO = "nc";

//Parada
const PARADAS = "tp";

//TAXIS
const TAXIS = "tx";


function obtemLinhaPorId($params) {
	try {
		echo LinhaService::getJSONPorId($params);
	} catch (Exception $e) {
		echo '{"' .  $e->getMessage() . '"}';
	}	
}

function obtemLinhasPorNomeOuCodigo($params, $tipo) {
	try {
		if ($tipo == Linha::TIPO_ONIBUS) {
			echo LinhaService::getJSONOnibus($params);
		} else if ($tipo == Linha::TIPO_LOTACAO) {
			echo LinhaService::getJSONLotacao($params);
		} else {
			throw new Exception('Tipo de linha desconhecido.');
		}
	} catch (Exception $e) {
		echo '{"' .  $e->getMessage() . '"}';
	}
}

function obtemParadas($params) {
	try {
		$filter = array("(", ")");
		$paramsFiltered = str_replace($filter, "", $params);
		
		$latlngs = explode(",",$paramsFiltered);

		$bounds = new Bound();
		$bounds->setLatitudeI($latlngs[0]);
		$bounds->setLongitudeI($latlngs[1]);
		$bounds->setLatitudeF($latlngs[2]);
		$bounds->setLongitudeF($latlngs[3]);
		
		echo ParadaService::getJSONParadas($bounds);
	} catch (Exception $e) {
		echo '{"' .  $e->getMessage() . '"}';
	}
}

function obtemTaxis($params) {
	try {
		$filter = array("(", ")");
		$paramsFiltered = str_replace($filter, "", $params);
		
		$latlngs = explode(",",$paramsFiltered);
		
		$bounds = new Bound();
		$bounds->setLatitudeI($latlngs[0]);
		$bounds->setLongitudeI($latlngs[1]);
		$bounds->setLatitudeF($latlngs[2]);
		$bounds->setLongitudeF($latlngs[3]);
		
		echo TaxiService::getJSONTaxis($bounds);
	} catch (Exception $e) {
		echo '{"' .  $e->getMessage() . '"}';
	}
}


$action = $_GET['a'];

if (array_key_exists('p',$_GET)) {
	$params = $_GET['p'];
}

if (array_key_exists('t',$_GET)) {
	$tipo = strtoupper($_GET['t']);
}

switch ($action) {
	case ID_LINHA:
		if (!is_numeric($params)) {
			echo '{ "p deve ser num�rico" }';
			return;
		}	
		obtemLinhaPorId($params);
		break;
	case NOME_CODIGO:
		obtemLinhasPorNomeOuCodigo($params, $tipo);
		break;
	case PARADAS:
		obtemParadas($params);
		break;
	case TAXIS:
		obtemTaxis($params);
		break;
	default:
		echo '{ "A��o inv�lida" }';
}
?>