<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}

if (isset($_POST['uf'])) {
    $estadoId = $_POST["uf"];
    $cidades = $mapper->loadCitiesInState($estadoId);
    $estado = $mapper->getEstadoByIdEncrypted($estadoId);
    if ($estado != null){
        echo '<option value="null">Selecione uma cidade...</option>';
        echo '<option value="null" disabled></option>';
        foreach ($cidades as $cidade){
            echo '<option value = ' . $cidade->getCipherId()  . '>' . $cidade->getNome() . '</option>';
        }
    } else {
        return 'Estado não encontrado';
    }
}