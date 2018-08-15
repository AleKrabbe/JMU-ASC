<?php
session_start();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}

if (isset($_POST['OM'])) {
    $OMid = $_POST["OM"];
    $selectedOM = $mapper->getOMbyIdEncrypted($OMid);
    if ($selectedOM != null){
        $postos = $mapper->getPostosByIdFa($selectedOM->getForcaArmada()->getId());
        echo '<option value="null">Selecione um posto...</option>';
        foreach ($postos as $posto){
            echo '<option value = ' . $posto->getIdEncrypted()  . '>' . $posto->getNome() . '</option>';
        }
    } else {
        echo 'OM Não Encontrada';
    }
}