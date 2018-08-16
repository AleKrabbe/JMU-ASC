<?php
session_start();
include("../controller/loginFuncs.php");
\asc\checkLogin();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}

if (isset($_GET['type'])) {
    if ($_GET['type'] == 'permanente') {
        $result = $mapper->fetchNomesConselho(3);
    } elseif ($_GET['type'] == 'especial') {
        $result = $mapper->fetchNomesConselho(4);
    }
}

?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ASC | Novo Conselho</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../css/plugins/dataTables/datatables.min.css" rel="stylesheet">
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
                <h2>Cadastro Militar</h2>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="../index.php">Principal</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a>Novo</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Conselho
                            <?php
                            if (isset($_GET['type'])) {
                                if ($_GET['type'] == 'permanente') {
                                    echo 'Permanente';
                                } elseif ($_GET['type'] == 'especial') {
                                    echo 'Especial';
                                } else {
                                    echo '';
                                }
                            }
                            ?>
                        </strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Entre com as informações do conselho</h5>
                        </div>
                        <div class="ibox-content">
                            <form id="cadastro-conselho-form" method="post">
                                <div class="form-group row"><label class="col-sm-2 col-form-label">Tipo&nbsp;<span style="color: red">*</span></label>
                                    <div class="col-sm-10">
                                        <select id="nome-conselho-dropdown" class="shouldValidate form-control m-b" name="nome" required>
                                            <option value="null"></option>
                                            <?php
                                            foreach ($result as $nome){
                                                echo '<option'.' value="'.$nome[0].'"'.'>'.$nome[1].' ('.$nome[2].')'.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row"><label class="col-sm-2 col-form-label">Posto&nbsp;<span style="color: red">*</span></label>
                                    <div class="col-sm-10">
                                        <select id="ajaxop" class="shouldValidate form-control m-b" name="posto" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <p>Os campos marcados com &nbsp;<span style="color: red">*</span>&nbsp; são obrigatórios.</p>
                                <div class="form-group row">
                                    <div class="col-sm-4 col-sm-offset-2">
                                        <button id="submit-btn" class="btn btn-primary btn-lg" type="submit" name="submit">Cadastrar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php";?>
    </div>
</div>

<!-- Mainly scripts -->
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.js"></script>