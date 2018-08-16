<?php
session_start();
include("../controller/loginFuncs.php");
\asc\checkLogin();
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ASC | Consulta</title>

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

        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title">
                            <h5>Tabela de
                                <?php
                                if (isset($_GET['data'])) {
                                    if ($_GET['data'] == 'militar') {
                                        echo 'militares cadastrados';
                                    } elseif ($_GET['data'] == 'om') {
                                        echo 'oraganizações militares cadastradas';
                                    } else {
                                        echo '"..."';
                                    }
                                }
                                ?>
                                </h5>
                        </div>
                        <div class="ibox-content">

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <?php
                                        if (isset($_GET['data'])) {
                                            if ($_GET['data'] == 'militar') {
                                                echo '<thead><tr><th>CPF</th><th>Nome</th><th>Email</th><th>Telefone</th><th>OM</th><th>Posto</th></tr></thead>';
                                            } elseif ($_GET['data'] == 'om') {
                                                echo '<thead><tr><th>Nome</th><th>Sigla</th><th>Telefone</th><th>Fax</th><th>Email</th><th>Comandante</th><th>Vínculo</th><th>Cidade/UF</th></tr></thead>';
                                            } else {
                                                echo 'Parâmetro ilegal';
                                            }
                                        }
                                    ?>
                                </table>
                            </div>

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

<script src="../js/plugins/dataTables/datatables.min.js"></script>
<script src="../js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

<!-- Page-Level Scripts -->
<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            language:{
                buttons: {
                    copyTitle: 'Tabela copiada',
                    copySuccess: {
                        _: '%d itens copiados',
                        1: '1 item copiado'
                    }
                },
                paginate: {
                    "first":      "Primeira",
                    "last":       "última",
                    "next":       "Próxima",
                    "previous":   "Anterior"
                },
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ entradas",
                info: "Mostrando _START_ a _END_ de _TOTAL_",
                emptyTable: "Nenhum dado disponível",
                zeroRecords: "Nenhum resultado",
                infoEmpty: "Nenhuma entrada a ser mostrada",
                infoFiltered: " - filtrado de _MAX_ entradas",
                loadingRecords: "Aguarde - carregando...",
                processing: "Os dados estão sendo processados...",
            },
            buttons: [
                {extend: 'copy', text: 'Copiar'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {extend: 'print',
                    text: 'Imprimir',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ],
            processing: true,
            serverSide: false,
            ajax: {
                "url": "../controller/loadData.php",
                "type": "POST",
                "data": {
                    "data" : parseURLParams(window.location.href)['data'][0]
                }
            }
        });

    });

    function parseURLParams(url) {
        var queryStart = url.indexOf("?") + 1,
            queryEnd   = url.indexOf("#") + 1 || url.length + 1,
            query = url.slice(queryStart, queryEnd - 1),
            pairs = query.replace(/\+/g, " ").split("&"),
            parms = {}, i, n, v, nv;

        if (query === url || query === "") return;

        for (i = 0; i < pairs.length; i++) {
            nv = pairs[i].split("=", 2);
            n = decodeURIComponent(nv[0]);
            v = decodeURIComponent(nv[1]);

            if (!parms.hasOwnProperty(n)) parms[n] = [];
            parms[n].push(nv.length === 2 ? v : null);
        }
        return parms;
    }
</script>


</body>
</html>
