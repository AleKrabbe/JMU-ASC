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
} elseif ($_REQUEST['data'] == "conselho_permanente"){
    $dataFetched = $mapper->getConselhosPermanentes();
} elseif ($_REQUEST['data'] == "conselho_especial"){
    $dataFetched = $mapper->getConselhosEspeciais();
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
        $nestedData[] = "<a style='font-size: 1.5em; color: #676a6c' href='editar-militar.php?id=".$militar->getCpfEncrypted()."'><i class=\"far fa-edit\"></i></a>";

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
        $nestedData[] = "<a style='font-size: 1.5em; color: #676a6c' href='editar-om.php?id=".$om->getIdEncrypted()."'><i class=\"far fa-edit\"></i></a>";

        $data[] = $nestedData;
    }
} elseif ($_REQUEST['data'] == 'conselho_permanente'){
    foreach ($dataFetched as $conselho_permanente) {
        $nestedData = array();
        $militares = $conselho_permanente->getMilitares();

        $nestedData[] = $conselho_permanente->getTrimestre() . "&ordm / " . $conselho_permanente->getAno();
        $nestedData[] = $conselho_permanente->getFa()->getNome();
        $nestedData[] = "<strong>" . $militares[0][4] . "</strong>" . " " . $militares[0][0]->getNome();
        $nestedData[] = "<strong>" . $militares[1][4] . "</strong>" . " " . $militares[1][0]->getNome();
        $nestedData[] = "<strong>" . $militares[2][4] . "</strong>" . " " . $militares[2][0]->getNome();
        $nestedData[] = "<strong>" . $militares[3][4] . "</strong>" . " " . $militares[3][0]->getNome();
        $nestedData[] = "<strong>" . $militares[4][4] . "</strong>" . " " . $militares[4][0]->getNome();
        $nestedData[] = "<strong>" . $militares[5][4] . "</strong>" . " " . $militares[5][0]->getNome();
        $nestedData[] = "<a style='font-size: 1.5em; color: #676a6c' href='novo-conselho.php?id=".$conselho_permanente->getCipheredIdConselho()."&type=permanente'><i class=\"far fa-edit\"></i></a>";

        $data[] = $nestedData;
    }
} elseif ($_REQUEST['data'] == 'conselho_especial'){
    foreach ($dataFetched as $conselho_especial) {
        $nestedData = array();
        $militares = $conselho_especial->getMilitares();

        $nestedData[] = $conselho_especial->getProcesso();
        $nestedData[] = $conselho_especial->getFa()->getNome();
        $nestedData[] = "<strong>" . $militares[0][4] . "</strong>" . " " . $militares[0][0]->getNome();
        $nestedData[] = "<strong>" . $militares[1][4] . "</strong>" . " " . $militares[1][0]->getNome();
        $nestedData[] = "<strong>" . $militares[2][4] . "</strong>" . " " . $militares[2][0]->getNome();
        $nestedData[] = "<strong>" . $militares[3][4] . "</strong>" . " " . $militares[3][0]->getNome();
        $nestedData[] = "<strong>" . $militares[4][4] . "</strong>" . " " . $militares[4][0]->getNome();
        $nestedData[] = "<strong>" . $militares[5][4] . "</strong>" . " " . $militares[5][0]->getNome();
        $nestedData[] = "<a style='font-size: 1.5em; color: #676a6c' href='novo-conselho.php?id=".$conselho_especial->getCipheredIdConselho()."&type=especial'><i class=\"far fa-edit\"></i></a>";

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