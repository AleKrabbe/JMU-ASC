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
    <link href="../css/plugins/steps/jquery.steps.css" rel="stylesheet">
    <link href="../css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
    <link href="../css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="../css/plugins/toastr/toastr.min.css" rel="stylesheet"/>
    <link href="../css/plugins/dualListbox/bootstrap-duallistbox.min.css" rel="stylesheet">
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

                            <form id="form" action="#" class="wizard">
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
                                            <div class="form-group" id="data_sorteio">
                                                <label>Data do Sorteio&nbsp;<span style="color: red">*</span></label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control" data-date-format="dd/mm/yyyy">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group" id="data_compromisso">
                                                <label>Data do Compromisso&nbsp;<span style="color: red">*</span></label>
                                                <div class="input-group date">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                                    <input type="text" class="form-control" data-date-format="dd/mm/yyyy">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                        "<input name=\"numero_processo\" type=\"text\" class=\"numero-proceso form-control\" pattern=\".{34,34}\" title=\"0000000-00.0000.7.09.009\">";
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
                                                <label>Selecione os juizes militares&nbsp;<span style="color: red">*</span></label>
                                                <select id="juizes-conselho-dropdown" class="form-control m-b dual_select" name="juizes" multiple>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <h1>Confirmar</h1>
                                <fieldset>
                                    <h2>Terms and Conditions</h2>
                                    <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">I agree with the Terms and Conditions.</label>
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

<?php
if ($_GET['type'] == 'especial') {
    echo "<script>" .
        "$(\".numero-proceso\").mask(\"0000000-00.0000.0.00.0000.0.00.000\", {placeholder: \"_______-__.____.7.09.009\", reverse: true});" .
        "</script>";
}
?>
<script>
    $(document).ready(function(){
        var form = $("#form");
        form.steps({
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex)
            {
                form.validate().settings.ignore = ":disabled,:hidden";
                if (currentIndex === 0 && $('#nome-conselho-dropdown').val() === 'null') {
                    toastr.error('Selecione o tipo do novo conselho.', 'Erro!');
                    $('#nome-conselho-dropdown').parent().addClass('has-error');
                    return false;
                } else if ($('#nome-conselho-dropdown').val() !== 'null') {
                    $('#nome-conselho-dropdown').parent().removeClass('has-error');
                }

                if(currentIndex === 1 && $("#data_sorteio input").val() === ''){
                    toastr.error('Selecione uma data de sorteio.', 'Erro!');
                    $('#data_sorteio').children('div').addClass('data-erro');
                    return false;
                } else if ($("#data_sorteio input").val() !== '') {
                    $('#data_sorteio').children('div').removeClass('data-erro');
                }

                if(currentIndex === 1 && $("#data_compromisso input").val() === ''){
                    $('#data_compromisso').children('div').addClass('data-erro');
                    return false;
                } else if ($("#data_compromisso input").val() !== '') {
                    $('#data_compromisso').children('div').removeClass('data-erro');
                }

                return form.valid();
            },
            onFinishing: function (event, currentIndex)
            {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex)
            {
                alert("Submitted!");
            },
            labels: {
                cancel: "Cancelar",
                current: "passo atual:",
                pagination: "Paginação",
                finish: "Pronto",
                next: "Próximo",
                previous: "Anterior",
                loading: "Carregando ..."
            }
        });
        $.fn.datepicker.defaults.language = 'pt-BR';
        $("#data_sorteio .input-group.date, #data_compromisso .input-group.date").datepicker({
            todayBtn: "linked",
            todayHighlight: true,
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true,
            format: "dd/mm/yyyy"
        });

        $(".dual_select").bootstrapDualListbox({
            selectorMinimalHeight: 160,
            moveOnSelect: false,
            infoTextFiltered: "<span class=\"label label-warning\">Filtrado</span> {0} de {1}",
            infoTextEmpty: "Lista vazia",
            infoText: "Mostrando {0} opções",
            moveSelectedLabel: "Mover os selecionados",
            moveAllLabel: "Mover todos",
            removeSelectedLabel: "Remover os selecionados",
            removeAllLabel: "Remover todos",
            filterPlaceHolder: "Buscar",
            filterTextClear: "Mostrar tudo",
            nonSelectedListLabel: 'Militares disponíveis',
            selectedListLabel: 'Militares selecionados'
        }).on('change', function(){
            var size = $(this).find(":selected").length;
            if(size > 3){
                $(this).find(":selected").each(function(ind, sel){
                    if(ind > 2)
                        $(this).prop("selected", false)
                });
                $(this).bootstrapDualListbox('refresh', true);
            }
        });

        var presidentes;
        var suplentes;
        var juizes;
        // Carrega os militares baseado no conselho escolhido.
        $('#nome-conselho-dropdown').change(function () {
            var id = $(this).val();
            if (id != null){
                $("#presidente-conselho-dropdown").html("<option>Carregando ...</option>");
                $.post("../controller/loadMilitares.php", {id_nome_sigla: id}, function (data, status) {
                    var militares = JSON.parse(data);
                    presidentes = militares['presidentes'];
                    suplentes = militares['suplentes'];
                    juizes = militares['juizes'];

                    var option = '<option></option>';
                    for (var i = 0; i < presidentes.length; i++) {
                        option += '<option value="'+ presidentes[i][0] + '">' + presidentes[i][1] + ' (' + presidentes[i][2] + ')</option>';
                    }
                    $("#presidente-conselho-dropdown").html(option);

                    option = '<option></option>';
                    for (var i = 0; i < suplentes.length; i++) {
                        option += '<option value="'+ suplentes[i][0] + '">' + suplentes[i][1] + ' (' + suplentes[i][2] + ')</option>';
                    }
                    $("#suplente-conselho-dropdown").html(option);

                    option = '';
                    for (var i = 0; i < juizes.length; i++) {
                        option += '<option value="'+ juizes[i][0] + '">' + juizes[i][1] + ' (' + juizes[i][2] + ')</option>';
                    }
                    $("#juizes-conselho-dropdown").html(option);

                    $('.chosen-select').chosen({width: "100%"});
                    $(".dual_select").bootstrapDualListbox('refresh', true);
                });
            }
        });

        $("#presidente-conselho-dropdown").change(function () {
            var id = $(this).val();
            option = '<option></option>';
            for (var i = 0; i < suplentes.length; i++) {
                if (suplentes[i][0] != id) {
                    option += '<option value="'+ suplentes[i][0] + '">' + suplentes[i][1] + ' (' + suplentes[i][2] + ')</option>';
                }
            }
            $('#suplente-conselho-dropdown').empty().html(option).trigger("chosen:updated");
        });
    });
</script>
</body>
</html>