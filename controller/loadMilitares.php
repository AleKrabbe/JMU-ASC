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
}

function match_my_string($needle, $haystack) {
    if (strpos($haystack, $needle) !== false) return true;
    else return false;
}