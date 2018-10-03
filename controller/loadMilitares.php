<?php
session_start();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}

if (isset($_POST['id_nome_sigla'])) {
    $idNomeSigla = $_POST["id_nome_sigla"];
    $nomeSigla = $mapper->fetchNomeSiglaByIdEncrypted($idNomeSigla);
    if ($nomeSigla != null){
        // ID das Forças Armadas:
        // 1 - Exército
        // 2 - Marinha
        // 3 - Aeronáutica

        if (match_my_string("Exército", $nomeSigla['nome'])) {
            $idFA = 1;
        } elseif (match_my_string("Marinha", $nomeSigla['nome'])) {
            $idFA = 2;
        } elseif (match_my_string("Aeronáutica", $nomeSigla['nome'])) {
            $idFA = 3;
        } else {
            $idFA = 0;
        }

        if ($idFA > 0) {
            $militares = $mapper->getAllMilitaresFromFA($idFA);
            $militares_JSON = array();
            $militares_presidente = array();
            $militares_suplente = array();
            $militares_juizes = array();
            foreach ($militares as $militar){
                if ($militar->getPosto()->getRank() >= 4){
                    array_push($militares_presidente, [$militar->getCpfEncrypted(), $militar->getNome(), $militar->getPosto()->getNome()]);
                    array_push($militares_suplente, [$militar->getCpfEncrypted(), $militar->getNome(), $militar->getPosto()->getNome()]);
                }
            }
            foreach ($militares as $militar){
                if ($militar->getPosto()->getRank() < 4){
                    array_push($militares_juizes, [$militar->getCpfEncrypted(), $militar->getNome(), $militar->getPosto()->getNome()]);
                }
            }
            $militares_JSON['presidentes'] = $militares_presidente;
            $militares_JSON['suplentes'] = $militares_suplente;
            $militares_JSON['juizes'] = $militares_juizes;
            echo json_encode($militares_JSON);
        } else {
            echo '<option value = ' . null  . '>' . "Não foi possível encontrar a força armada com base no nome do conselho" . '</option>';
        }
    } else {
        echo 'Conselho não encontrado';
    }
} else if (isset($_POST['id_conselho']) && isset($_POST['tipo_conselho'])) {
    $id_conselho = $_POST['id_conselho'];
    $tipo_conselho = $_POST['tipo_conselho'];

    if ($tipo_conselho == "permanente"){
        $conelho = $mapper->getConselhoPermanenteFromID($id_conselho);
    } else if ($tipo_conselho == "especial"){
        $conelho = $mapper->getConselhoEspecialFromID($id_conselho);
    }

    $militares = $conelho->getMilitares();
    $militares_JSON = array();
    $militares_presidente = array();
    $militares_suplente = array();
    $militares_juizes = array();
    $suplente_juizes = array();

    array_push($militares_presidente, [$militares[0][0]->getCpfEncrypted(), $militares[0][0]->getNome(), $militares[0][0]->getPosto()->getNome()]);
    array_push($militares_suplente, [$militares[1][0]->getCpfEncrypted(), $militares[1][0]->getNome(), $militares[1][0]->getPosto()->getNome()]);
    array_push($militares_juizes, [$militares[2][0]->getCpfEncrypted(), $militares[2][0]->getNome(), $militares[2][0]->getPosto()->getNome()]);
    array_push($militares_juizes, [$militares[3][0]->getCpfEncrypted(), $militares[3][0]->getNome(), $militares[3][0]->getPosto()->getNome()]);
    array_push($militares_juizes, [$militares[4][0]->getCpfEncrypted(), $militares[4][0]->getNome(), $militares[4][0]->getPosto()->getNome()]);
    array_push($suplente_juizes, [$militares[5][0]->getCpfEncrypted(), $militares[5][0]->getNome(), $militares[5][0]->getPosto()->getNome()]);

    $militares_JSON['presidente'] = $militares_presidente;
    $militares_JSON['suplente_presidente'] = $militares_suplente;
    $militares_JSON['juizes'] = $militares_juizes;
    $militares_JSON['suplente_juizes'] = $suplente_juizes;
    echo json_encode($militares_JSON);
}

function match_my_string($needle, $haystack) {
    if (strpos($haystack, $needle) !== false) return true;
    else return false;
}