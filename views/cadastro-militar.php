<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();
include("../controller/loginFuncs.php");
\asc\checkLogin();

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = \asc\MySQL_DataMapper::getInstance();
    $result = $mapper->getOMs();
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
                            <strong>Militar</strong>
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
                                <h5>Entre com as informações do militar</h5>
                            </div>
                            <div class="ibox-content">
                                <form id="cadastro-militar-form" method="post">
                                    <div class="form-group  row"><label class="col-sm-2 col-form-label">CPF&nbsp;<span style="color: red">*</span></label>
                                        <div class="col-sm-10"><input name="cpf" type="text" class="shouldValidate cpf form-control" pattern=".{14,14}" title="000.000.000-00" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group  row"><label class="col-sm-2 col-form-label">Nome&nbsp;<span style="color: red">*</span></label>
                                        <div class="col-sm-10"><input name="fname" type="text" class="shouldValidate fname nome form-control" maxlength="45" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group  row"><label class="col-sm-2 col-form-label">Sobrenome&nbsp;<span style="color: red">*</span></label>
                                        <div class="col-sm-10"><input name="lname" type="text" class="shouldValidate lname nome form-control" maxlength="100" required></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group  row"><label class="col-sm-2 col-form-label">Celular</label>
                                        <div class="col-sm-10"><input name="telefone" type="tel" class="telefone phone_with_ddd form-control"></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group  row"><label class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10"><input name="email" type="email" class="email form-control" maxlength="50"></div>
                                    </div>
                                    <div class="hr-line-dashed"></div>
                                    <div class="form-group row"><label class="col-sm-2 col-form-label">Organização Militar&nbsp;<span style="color: red">*</span></label>
                                        <div class="col-sm-10">
                                            <select id="om-dropdown" class="shouldValidate form-control m-b" name="organizacao_militar" required>
                                                <option value="null"></option>
                                                <?php
                                                $last_FA = null;
                                                foreach ($result as $OM){
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
    <script src="../js/plugins/toastr/toastr.min.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/my_js/validate_cadastro_militar.js"></script>
    <script>
        $(document).ready(function () {
            // Carrega os postos de uma força armada baseado na OM escolhida.
            $('#om-dropdown').change(function () {
                var id = $(this).val();
                $("#ajaxop").html("<option>Carregando ...</option>").parent().addClass('has-error');
                $.post("../controller/loadPostos.php", {OM: id}, function (data, status) {
                    $("#ajaxop").html(data);
                });
            });

            // Seta o foco no campo CPF
            $('.cpf').focus();
        });
    </script>

    </body>
    </html>

<?php
if (isset($_POST['submit'])) {
    try {
        $OM = $mapper->getOMbyIdEncrypted($_POST['organizacao_militar']);
        $posto = $mapper->getPostoByIdEncrypted($_POST['posto']);

        $militar = new \asc\Militar($_POST['cpf'], $_POST['fname'], $_POST['lname'], $OM, $posto);
        if (isset($_POST['email'])) {
            $militar->setEmail($_POST['email']);
        }
        if (isset($_POST['telefone'])) {
            $militar->setTelefone($_POST['telefone']);
        }
        $erro = $mapper->cadastrarMilitar($militar);
        if ($erro[0] === 0){
            echo '<script>toastr.success(\'Militar cadastrado com sucesso!\');</script>';
        } else {
            switch ($erro[0]) {
                case 1062:
                    echo '<script>toastr.error(\'Não foi possível cadastrar o militar. CPF já cadastrado\', \'Erro!\');</script>';
                    break;
                case 1406:
                    $campo = get_string_between($erro[1], "'", "'");
                    switch ($campo) {
                        case "cpf":
                            $campo = "CPF";
                            break;
                        case "fname":
                            $campo = "Nome";
                            break;
                        case "lname":
                            $campo = "Sobrenome";
                            break;
                        case "email":
                            $campo = "Email";
                            break;
                        case "telefone":
                            $campo = "Celular";
                            break;
                    }
                    echo '<script>toastr.error(\'Não foi possível cadastrar o militar. ' . $campo . ' é muito longo\', \'Erro!\');</script>';
                    break;
                default:
                    echo '<script>toastr.error(\'Não foi possível cadastrar o militar. Código do erro: ' . $erro[0] . '\', \'Erro!\');</script>';
                    break;
            }
        }
    } catch (SodiumException $e) {
        echo '<script>'.
            'toastr.error(\'Selecione um posto para o militar.\', \'Erro!\');'
            .'$(\'.cpf\').val(\''.$_POST['cpf'].'\');'
            .'$(\'.fname\').val(\''.$_POST['fname'].'\');'
            .'$(\'.lname\').val(\''.$_POST['lname'].'\');'
            .'$(\'.telefone\').val(\''.$_POST['telefone'].'\');'
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