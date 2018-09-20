<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

session_start();
include("../controller/loginFuncs.php");
\asc\checkLogin();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}

try{

    if (isset($_GET['type'])) {
        if ($_GET['type'] == 'permanente') {
            $result = $mapper->fetchNomesConselho(3);
        } elseif ($_GET['type'] == 'especial') {
            $result = $mapper->fetchNomesConselho(4);
        } else {
            throw new Exception("Parâmetro inválido");
        }
    } else {
        throw new Exception("Parâmetro inválido");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $infoConselho = $mapper->getInfodoConselhoByEncryptedNomeID($_POST['nome']);

        $presidente = $mapper->getMilitarFromEncryptedCPF($_POST['presidente']);
        $suplente_presidente = $mapper->getMilitarFromEncryptedCPF($_POST['suplente']);
        $suplente_juizes = $mapper->getMilitarFromEncryptedCPF($_POST['suplente-juizes']);
        $juiz_1 = $mapper->getMilitarFromEncryptedCPF($_POST['juizes'][0]);
        $juiz_2 = $mapper->getMilitarFromEncryptedCPF($_POST['juizes'][1]);
        $juiz_3 = $mapper->getMilitarFromEncryptedCPF($_POST['juizes'][2]);

        $militares = array(
                [$presidente ,$_POST['data_sorteio_presidente'], $_POST['data_compromisso_presidente']],
                [$suplente_presidente, $_POST['data_sorteio_suplente'], $_POST['data_compromisso_suplente']],
                [$juiz_1, $_POST['data_sorteio_juiz_1'], $_POST['data_compromisso_juiz_1']],
                [$juiz_2, $_POST['data_sorteio_juiz_2'], $_POST['data_compromisso_juiz_2']],
                [$juiz_3, $_POST['data_sorteio_juiz_3'], $_POST['data_compromisso_juiz_3']],
                [$suplente_juizes, $_POST['data_sorteio_juiz_suplente'], $_POST['data_compromisso_juiz_suplente']]);

        if ($infoConselho['tipo'] == "permanente") {
            $conselho = new \asc\ConselhoPermanente($infoConselho["nome"], $infoConselho["sigla"], $militares, $_POST["trimestre"]);
        } else if ($infoConselho['tipo'] == "especial") {
            $conselho = new \asc\ConselhoEspecial($infoConselho["nome"], $infoConselho["sigla"], $militares, $_POST["numero_processo"]);
        } else {
            throw new Exception("Erro ao cadastrar um novo conselho. Contate o administrador do sistema.");
        }

        $erro = $mapper->cadastrarConselho($conselho);

        exit();
    }

} catch (Exception $exc) {
    $msg = $exc->getMessage();
    require("../500.php");
    exit();
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
    <link href="../css/plugins/steps/jquery.steps.css" rel="stylesheet">
    <link href="../css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
    <link href="../css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet"/>
    <link href="../css/plugins/dualListbox/bootstrap-duallistbox.min.css" rel="stylesheet">
    <link href="../css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
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
                <h2>Cadastro de Conselho</h2>
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
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Novo Conselho</h5>
                        </div>
                        <div class="ibox-content">
                            <h2>
                                Entre com as informações do conselho
                            </h2>
                            <p>
                                Sigua os passos a seguir para cadastrar um novo conselho.
                            </p>

                            <form id="cadastro-conselho-form"  class="wizard" method="post">

                            <h1>Tipo</h1>
                            <fieldset>
                                <h2>Selecione a categoria do conselho</h2>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <label>Tipo&nbsp;<span style="color: red">*</span></label>
                                            <select id="nome-conselho-dropdown" class="form-control m-b" name="nome">
                                                <option value="null">Selecione um tipo de conselho</option>
                                                <?php
                                                foreach ($result as $nome){
                                                    echo '<option'.' value="'.$nome[0].'"'.'>'.$nome[1].' ('.$nome[2].')'.'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </fieldset>

                            <h1>Informações Gerais</h1>
                            <fieldset>
                                <h2>Informações gerais do novo conselho</h2>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php
                                            if ($_GET['type'] == 'permanente') {
                                                echo "<label>Trimestre&nbsp;<span style=\"color: red\">*</span></label>".
                                                    "<select id=\"trimestre-conselho-dropdown\" class=\"form-control m-b\" name=\"trimestre\">".
                                                    "<option value=\"1\">1&ordm</option>".
                                                    "<option value=\"2\">2&ordm</option>".
                                                    "<option value=\"3\">3&ordm</option>".
                                                    "<option value=\"4\">4&ordm</option>".
                                                    "</select>";
                                            } elseif ($_GET['type'] == 'especial') {
                                                echo "<label>N&ordm; do processo&nbsp;<span style=\"color: red\">*</span></label>".
                                                    "<input name=\"numero_processo\" type=\"text\" id=\"numero-processo\" class=\"form-control\" placeholder=\"_______-__.____.7.09.009\" title=\"0000000-00.0000.7.09.009\">";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h1>Membros</h1>
                            <fieldset>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Selecione o presidente do conselho&nbsp;<span style="color: red">*</span></label>
                                            <select id="presidente-conselho-dropdown" data-placeholder="Escolha um presidente..." class="form-control m-b chosen-select" name="presidente">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Selecione o suplente do presidente&nbsp;<span style="color: red">*</span></label>
                                            <select id="suplente-conselho-dropdown" data-placeholder="Escolha um suplente..." class="form-control m-b chosen-select" name="suplente">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr-line-dashed" style="background-color: #7d7d7d;margin-bottom: 30px"></div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Selecione o suplente dos juizes militares&nbsp;<span style="color: red">*</span></label>
                                            <select id="suplente-juizes-conselho-dropdown" data-placeholder="Escolha um suplente para os juizes..." class="form-control m-b chosen-select" name="suplente-juizes">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Selecione os juizes militares&nbsp;<span style="color: red">*</span></label>
                                            <select id="juizes-conselho-dropdown" class="form-control m-b dual_select" name="juizes[]" multiple>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h1>Confirmar</h1>
                            <fieldset>
                                <h2>Confirme as datas de sorteio e compromisso</h2>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Presidente</label>
                                            <input type="text" disabled="" class="form-control" id="nome_presidente">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_sorteio_presidente">
                                            <label>Data do Sorteio&nbsp;<span style="color: red">*</span></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_presidente">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_presidente">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_presidente">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Suplente do Presidente</label>
                                            <input type="text" disabled="" class="form-control" id="nome_suplente">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_sorteio_suplente">
                                            <label>Data do Sorteio&nbsp;<span style="color: red">*</span></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_suplente">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_suplente">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_suplente">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Juiz Militar 1</label>
                                            <input type="text" disabled="" class="form-control" id="nome_juiz_1">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_sorteio_juiz_1">
                                            <label>Data do Sorteio&nbsp;<span style="color: red">*</span></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_1">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_1">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Juiz Militar 2</label>
                                            <input type="text" disabled="" class="form-control" id="nome_juiz_2">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_sorteio_juiz_2">
                                            <label>Data do Sorteio&nbsp;<span style="color: red">*</span></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_2">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_2">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Juiz Militar 3</label>
                                            <input type="text" disabled="" class="form-control" id="nome_juiz_3">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_sorteio_juiz_3">
                                            <label>Data do Sorteio&nbsp;<span style="color: red">*</span></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_3">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_3">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_3">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label>Suplente dos Juizes</label>
                                            <input type="text" disabled="" class="form-control" id="nome_juiz_suplente">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_sorteio_juiz_suplente">
                                            <label>Data do Sorteio&nbsp;<span style="color: red">*</span></label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_suplente">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_suplente">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_suplente">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

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
<script src="../js/plugins/toastr/toastr.min.js"></script>
<script src="../js/jquery.mask.js"></script>

<!-- Steps -->
<script src="../js/plugins/steps/jquery.steps.min.js"></script>

<!-- Jquery Validate -->
<script src="../js/plugins/validate/jquery.validate.min.js"></script>

<!-- Date picker -->
<script src="../js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="../js/locales/datepicker-pt-BR.js" charset="UTF-8"></script>

<!-- Dual Listbox -->
<script src="../js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script>

<!-- Chosen -->
<script src="../js/plugins/chosen/chosen.jquery.js"></script>

<!-- Input Mask-->
<script src="../js/plugins/RobinHerbots/jquery.inputmask.bundle.js"></script>

<!--Validação do cadastro-->
<script src="../js/my_js/cadastro_conselho.js"></script>

</body>
</html>