<?php
session_start();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}

// storing  request (ie, get/post) global array to a variable
$requestData= $_REQUEST;

$data = array();

if ($_REQUEST['data'] == "militar"){
    $dataFetched = $mapper->getMilitares();
} elseif ($_REQUEST['data'] == "om"){
    $dataFetched = $mapper->getOMs();
} else {
    $dataFetched = array();
}

$totalData = count($dataFetched);
$totalFiltered = $totalData;

if ($_REQUEST['data'] == 'militar') {
    foreach ($dataFetched as $militar) {
        $nestedData = array();

        $nestedData[] = $militar->getCpf();
        $nestedData[] = $militar->getNome();
        $nestedData[] = $militar->getEmail();
        $nestedData[] = $militar->getTelefone();
        $nestedData[] = $militar->getOM()->getSigla();
        $nestedData[] = $militar->getPosto()->getSigla();

        $data[] = $nestedData;
    }
} elseif ($_REQUEST['data'] == 'om'){
    foreach ($dataFetched as $om) {
        $nestedData = array();

        $nestedData[] = $om->getNome();
        $nestedData[] = $om->getSigla();
        $nestedData[] = $om->getTelefone();
        $nestedData[] = $om->getFax();
        $nestedData[] = $om->getEmail();
        $nestedData[] = $om->getNomeComandante();
        $nestedData[] = $om->getVinculo()->getSigla();
        $nestedData[] = $om->getCidade()->getNome() . '/' .$om->getCidade()->getEstado()->getUf();

        $data[] = $nestedData;
    }
}

$json_data = array(
//    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
    "recordsTotal"    => intval( $totalData ),  // total number of records
    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format