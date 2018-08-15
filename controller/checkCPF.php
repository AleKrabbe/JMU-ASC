<?php
session_start();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}

if (isset($_POST['CPF'])) {
    $cpf = $_POST["CPF"];
    $exists = $mapper->checkMilitar($cpf);
    echo $exists;
}