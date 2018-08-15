<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../controller/loginFuncs.php");
\asc\checkLogin();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
    $result_om = $mapper->getVinculos();
    $result_estados = $mapper->getEstados();
} catch (\Exception $e) {
    echo $e->getMessage(), "\n";
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ASC | Cadastro</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet"/>
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
                        <a>Cadastro</a>
                    </li>
                    <li class="breadcrumb-item active">
                        <strong>Organização Militar</strong>
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
                            <h5>Entre com as informações da organização militar</h5>
                        </div>
                        <div class="ibox-content">
                            <form id="cadastro-om-form" method="post">
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Nome&nbsp;<span style="color: red">*</span></label>
                                    <div class="col-sm-10"><input name="nome" type="text" class="shouldValidate nome form-control" maxlength="100" required></div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Sigla&nbsp;<span style="color: red">*</span></label>
                                    <div class="col-sm-10"><input name="sigla" type="text" class="shouldValidate sigla form-control" maxlength="30" required></div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Nome do Comandante</label>
                                    <div class="col-sm-10"><input name="nome_comandante" type="text" class="nome-comandante form-control" maxlength="45"></div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Vínculo <span style="color: red">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="vinculo" id="om-dropdown" class="shouldValidate vinculo form-control m-b" required>
                                            <option value="null"></option>
                                            <?php
                                            $last_FA = null;
                                            foreach ($result_om as $OM){
                                                if ($OM->getForcaArmada()->getNome() != $last_FA) {
                                                    $last_FA = $OM->getForcaArmada()->getNome();
                                                    echo '<option disabled></option>';
                                                    echo '<option disabled>────────────────────  '.$last_FA.'  ────────────────────</option>';
                                                }
                                                echo '<option'.' value="'.$OM->getIdEncrypted().'"'.'>'.$OM->getNome().' ('.$OM->getSigla().')'.'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Telefone</label>
                                    <div class="col-sm-10"><input name="telefone" type="text" class="telefone telefone form-control" maxlength="10"></div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Fax</label>
                                    <div class="col-sm-10"><input name="fax" type="tel" class="fax form-control" maxlength="10"></div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10"><input name="email" type="email" class="email form-control" maxlength="45"></div>
                                </div><div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Estado <span style="color: red">*</span></label>
                                    <div class="col-sm-10">
                                        <select id="estado-dropdown" class="shouldValidate estado form-control m-b" name="uf" required>
                                            <option value="null"></option>
                                            <?php
                                            foreach ($result_estados as $estado){
                                                echo '<option value="'.$estado->getCipherId().'">'.$estado->getNome().'</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group  row"><label class="col-sm-2 col-form-label">Cidade <span style="color: red">*</span></label>
                                    <div class="col-sm-10">
                                        <select name="cidade" id="cidade-ajaxop" class="shouldValidate cidade form-control m-b" required>
                                        </select>
                                    </div>
                                </div>
                                <div class="hr-line-dashed"></div>
                                <div class="form-group row"><label class="col-sm-2 col-form-label">Força Armada</label>
                                    <div class="col-sm-10">
                                        <select name="fa" id="ajax_fa" class="shouldValidate form-control m-b" readonly="readonly">
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
<script src="../js/plugins/toastr/toastr.min.js"></script>
<script src="../js/jquery.mask.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.js"></script>
<script src="../js/my_js/validate_cadastro_om.js"></script>
<script>
    $(document).ready(function () {
        // Carrega a força armada baseado em uma OM escolhida.
        $('#om-dropdown').change(function () {
            var id = $(this).val();
            if (id != null){
                $("#ajax_fa").html("<option>Carregando ...</option>");
                $.post("../controller/loadFA.php", {OM: id}, function (data, status) {
                    $("#ajax_fa").html(data);
                });
            }
        });

        // Carrega as cidades baseado em um estado escolhido.
        $('#estado-dropdown').change(function () {
            var id = $(this).val();
            if (id != null){
                $("#cidade-ajaxop").html("<option>Carregando ...</option>");
                $.post("../controller/loadCidades.php", {uf: id}, function (data, status) {
                    $("#cidade-ajaxop").html(data);
                });
            }
        });

        // Seta o foco no campo Nome
        $('.nome').focus();
    });
</script>

<?php
if (isset($_POST['submit'])) {
    try {
        $OM = new \asc\OM($_POST['nome'], $_POST['sigla'], $mapper->getFAbyCypheredId($_POST['fa']));
        if (isset($_POST['nome_comandante'])) {
            $OM->setNomeComandante($_POST['nome_comandante']);
        }
        if (isset($_POST['vinculo'])) {
            $OM->setVinculo($mapper->getOMbyIdEncrypted($_POST['vinculo']));
        }
        if (isset($_POST['telefone'])) {
            $OM->setTelefone($_POST['telefone']);
        }
        if (isset($_POST['fax'])) {
            $OM->setFax($_POST['fax']);
        }
        if (isset($_POST['email'])) {
            $OM->setEmail($_POST['email']);
        }
        if (isset($_POST['cidade'])) {
            $OM->setCidade($mapper->getCidadeByIdEncrypted($_POST['cidade']));
        }
        $erro = $mapper->cadastrarOM($OM);
        if ($erro[0] === 0){
            echo '<script>toastr.success(\'Organização militar cadastrada com sucesso!\');</script>';
        } else {
            switch ($erro[0]) {
                case 1062:
                    echo '<script>toastr.error(\'Já existe uma organização militar cadastrada com esse nome\', \'Erro!\');</script>';
                    break;
                case 1406:
                    $campo = get_string_between($erro[1], "'", "'");
                    switch ($campo) {
                        case "nome_comandante":
                            $campo = "Nome do Comandante";
                            break;
                        case "vinculo":
                            $campo = "Vínculo";
                            break;
                        case "telefone":
                            $campo = "Telefone";
                            break;
                        case "fax":
                            $campo = "Fax";
                            break;
                        case "email":
                            $campo = "Email";
                            break;
                        case "cidade":
                            $campo = "Cidade";
                            break;
                        case "uf":
                            $campo = "Estado";
                            break;

                    }
                    echo '<script>toastr.error(\'Não foi possível cadastrar a OM. ' . $campo . ' é muito longo\', \'Erro!\');</script>';
                    break;
                default:
                    echo '<script>toastr.error(\'Não foi possível cadastrar a OM. Código do erro: ' . $erro[1] . '\', \'Erro!\');</script>';
                    break;
            }
        }
    } catch (SodiumException $e) {
        echo '<script>'.
            'toastr.error(\'Algo inesperado aconteceu, tente novamente.\', \'Erro!\');'
            .'$(\'.nome\').val(\''.$_POST['nome'].'\');'
            .'$(\'.sigla\').val(\''.$_POST['sigla'].'\');'
            .'$(\'.nome_comandante\').val(\''.$_POST['nome_comandante'].'\');'
            .'$(\'.telefone\').val(\''.$_POST['telefone'].'\');'
            .'$(\'.fax\').val(\''.$_POST['fax'].'\');'
            .'$(\'.email\').val(\''.$_POST['email'].'\');'
            .'</script>';
    }
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
?>