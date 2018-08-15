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
        $FA = $selectedOM->getForcaArmada()->getNome();
        echo '<option value = ' . $selectedOM->getForcaArmada()->getIdEncrypted()  . '>' . $FA . '</option>';
    } else {
        echo 'OM não encontrada';
    }
}