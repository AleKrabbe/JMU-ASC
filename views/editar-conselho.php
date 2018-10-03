<?php
session_start();
include("../controller/loginFuncs.php");
\asc\checkLogin();

require_once '../controller/database/MySQL_DataMapper.php';

try {

    $mapper = \asc\MySQL_DataMapper::getInstance();

    if (isset($_GET['id']) && isset($_GET['tipo'])) {

        if ($_GET['tipo'] == 'permanente') {
            $tipo = "permanente";
            $title = "Permanente";
            $conelho = $mapper->getConselhoPermanenteFromEncryptedID($_GET['id']);
        } elseif ($_GET['tipo'] == 'especial') {
            $tipo = "especial";
            $title = "Especial";
            $conelho = $mapper->getConselhoEspecialFromEncryptedID($_GET['id']);
        } else {
            throw new Exception("Parâmetro inválido");
        }
    } else {
        throw new Exception("Parâmetro inválido");
    }

} catch (Exception $e) {
    $msg = $e->getTrace();
    $msg = serialize($msg);
    require("../500.php");
    exit();
}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ASC | Editar Conselho</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="../images/favicon_package_v0.16/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon_package_v0.16/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon_package_v0.16/favicon-16x16.png">
    <link rel="manifest" href="../images/favicon_package_v0.16/site.webmanifest">
    <link rel="mask-icon" href="../images/favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

</head>

<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <?php include "menu.php";?>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Edição de Conselho</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="../index.php">Principal</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a>Consulta</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Conselho <?= $title ?></strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Editar Conselho</h5>
                        </div>
                        <div class="ibox-content">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php";?>
    </div>
</div>

</body>
</html>

