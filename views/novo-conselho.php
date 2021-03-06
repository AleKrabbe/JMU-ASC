<?php
//ini_set('display_errors',1);
//ini_set('display_startup_erros',1);
//error_reporting(E_ALL);

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
            $title = "Permanente";
            if (isset($_GET['id'])){
                $conselho = $mapper->getConselhoPermanenteFromEncryptedID($_GET['id']);
                $trimestre_edit = $conselho->getTrimestre();
            }
        } elseif ($_GET['type'] == 'especial') {
            $result = $mapper->fetchNomesConselho(4);
            $title = "Especial";
            if (isset($_GET['id'])){
                $conselho = $mapper->getConselhoEspecialFromEncryptedID($_GET['id']);
                $processo_edit = $conselho->getProcesso();
            }
        } else {
            throw new Exception("Parâmetro inválido");
        }
    } else {
        throw new Exception("Parâmetro inválido");
    }

    $edit = false;
    if (isset($conselho)){
        $edit = true;
        $nome = $conselho->getIdNomeSigla();
        $militares = $conselho->getMilitares();
        $presidente = $militares[0][0];
        $suplente_presidente = $militares[1][0];
        $juiz_1 = $militares[2][0];
        $juiz_2 = $militares[3][0];
        $juiz_3 = $militares[4][0];
        $suplente_juiz = $militares[5][0];
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
                [$presidente ,$_POST['data_sorteio_presidente'], $_POST['data_compromisso_presidente'], 1, $presidente->getPosto()->getNome()],
                [$suplente_presidente, $_POST['data_sorteio_suplente'], $_POST['data_compromisso_suplente'], 2, $suplente_presidente->getPosto()->getNome()],
                [$juiz_1, $_POST['data_sorteio_juiz_1'], $_POST['data_compromisso_juiz_1'], 3, $juiz_1->getPosto()->getNome()],
                [$juiz_2, $_POST['data_sorteio_juiz_2'], $_POST['data_compromisso_juiz_2'], 3, $juiz_2->getPosto()->getNome()],
                [$juiz_3, $_POST['data_sorteio_juiz_3'], $_POST['data_compromisso_juiz_3'], 3, $juiz_3->getPosto()->getNome()],
                [$suplente_juizes, $_POST['data_sorteio_juiz_suplente'], $_POST['data_compromisso_juiz_suplente'], 4, $suplente_juizes->getPosto()->getNome()]);

        if ($infoConselho['tipo'] == "permanente") {
            $conselho = new \asc\ConselhoPermanente($infoConselho["nome"], $infoConselho["sigla"], $_POST["trimestre"], date("Y"));
        } else if ($infoConselho['tipo'] == "especial") {
            $conselho = new \asc\ConselhoEspecial($infoConselho["nome"], $infoConselho["sigla"], $_POST["numero_processo"]);
        } else {
            throw new Exception("Erro ao cadastrar um novo conselho. Contate o administrador do sistema.");
        }

        $conselho->setMilitares($militares);
        $conselho->setIdNomeSigla($mapper->decrypt($_POST['nome']));

        $erro = $mapper->cadastrarConselho($conselho);

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
                            <h5>Novo Conselho</h5>
                        </div>
                        <div class="ibox-content">
                            <h2>
                                Entre com as informações do conselho
                            </h2>
                            <p>
                                <?php
                                    if ($edit) {
                                        $msg = "Altere as informações do conselho como desejar e clique em salvar para finalizar.";
                                    } else {
                                        $msg = "Sigua os passos a seguir para cadastrar um novo conselho.";
                                    }

                                    echo $msg;
                                ?>
                            </p>

                            <form id="cadastro-conselho-form"  class="wizard" method="post">

                            <h1>Tipo</h1>
                            <fieldset>
                                <h2>Selecione a categoria do conselho</h2>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group">
                                            <?php
                                                if ($edit){
                                                    echo '<input id="id-conselho" type="hidden" value="'.$conselho->getIdConselho().'">';
                                                    if ($_GET['type'] == 'permanente') {
                                                        echo '<input id="tipo-conselho" type="hidden" value="permanente">';
                                                    } else if ($_GET['type'] == 'especial') {
                                                        echo '<input id="tipo-conselho" type="hidden" value="especial">';
                                                    }
                                                }
                                            ?>
                                            <label>Tipo&nbsp;<span style="color: red">*</span></label>
                                            <select id="nome-conselho-dropdown" class="form-control m-b" name="nome">
                                                <option value="null">Selecione um tipo de conselho</option>
                                                <?php
                                                foreach ($result as $nome_info){
                                                    if ($edit && $mapper->decrypt($nome_info[0]) == $nome){
                                                        echo '<option'.' value="'.$nome_info[0].'"'.' selected>'.$nome_info[1].' ('.$nome_info[2].')'.'</option>';
                                                    } else {
                                                        echo '<option'.' value="'.$nome_info[0].'"'.'>'.$nome_info[1].' ('.$nome_info[2].')'.'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <p>Os campos marcados com &nbsp;<span style="color: red">*</span>&nbsp; são obrigatórios.</p>
                            </fieldset>

                            <h1>Informações Gerais</h1>
                            <fieldset>
                                <h2>Informações gerais do novo conselho</h2>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <?php
                                            if ($_GET['type'] == 'permanente') {
                                                $trimestre = ceil(date('n', time())/3);
                                                echo "<label>Trimestre&nbsp;<span style=\"color: red\">*</span></label>".
                                                    "<select id=\"trimestre-conselho-dropdown\" class=\"form-control m-b\" name=\"trimestre\">";
                                                for ($i = 0; $i < 4; $i++){
                                                    if ($edit && (($i + 1) == $trimestre_edit)){
                                                        echo "<option value=\"". ($i+1) ."\" selected>". ($i+1) ."&ordm</option>";
                                                    } else {
                                                        if (!$edit && ($i + 1 == $trimestre)){
                                                            echo "<option value=\"". ($i+1) ."\" selected>". ($i+1) ."&ordm</option>";
                                                        } else {
                                                            echo "<option value=\"". ($i+1) ."\">". ($i+1) ."&ordm</option>";
                                                        }
                                                    }
                                                }
                                                echo "</select>";
                                            } elseif ($_GET['type'] == 'especial') {
                                                echo "<label>N&ordm; do processo&nbsp;<span style=\"color: red\">*</span></label>".
                                                    "<input name=\"numero_processo\" type=\"text\" id=\"numero-processo\" class=\"form-control\" placeholder=\"_______-__.____.7.09.009\" title=\"0000000-00.0000.7.09.009\">";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <p>Os campos marcados com &nbsp;<span style="color: red">*</span>&nbsp; são obrigatórios.</p>
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
                                <p>Os campos marcados com &nbsp;<span style="color: red">*</span>&nbsp; são obrigatórios.</p>
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
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_presidente" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_presidente">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_presidente" autocomplete="off">
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
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_suplente" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_suplente">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_suplente" autocomplete="off">
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
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_1" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_1">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_1" autocomplete="off">
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
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_2" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_2">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_2" autocomplete="off">
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
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_3" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_3">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_3" autocomplete="off">
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
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_sorteio_juiz_suplente" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group" id="data_compromisso_juiz_suplente">
                                            <label>Data do Compromisso</label>
                                            <div class="input-group date">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control" data-date-format="dd/mm/yyyy" name="data_compromisso_juiz_suplente" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p>Os campos marcados com &nbsp;<span style="color: red">*</span>&nbsp; são obrigatórios.</p>
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

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($erro[0] === 0){
        echo '<script>toastr.success(\'Conselho cadastrado com sucesso!\');</script>';
    } else {
        echo '<script>toastr.error(\'Não foi possível cadastrar o conselho. Código do erro: ' . $erro[0] . '\', \'Erro!\');</script>';
    }
}
?>

</body>
</html>