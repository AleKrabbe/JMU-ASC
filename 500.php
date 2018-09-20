<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ASC | 500 Error</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../css/animate.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon_package_v0.16/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon_package_v0.16/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon_package_v0.16/favicon-16x16.png">
    <link rel="manifest" href="images/favicon_package_v0.16/site.webmanifest">
    <link rel="mask-icon" href="images/favicon_package_v0.16/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

</head>

<body class="gray-bg">


    <div class="middle-box text-center animated fadeInDown">
        <h1>500</h1>
        <h3 class="font-bold">Erro Interno</h3>

        <div class="error-desc">
            <?php
                if ($msg == ""){
                    $msg = "O servidor encontrou algo inesperado e não permitiu que a requisição fosse completada.";
                }
                echo $msg;
            ?>
            </br>
            </br>
            Voltar a página principal: <br/><a href="principal.php" class="btn btn-primary m-t">Principal</a>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="../js/jquery-3.1.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.js"></script>

</body>

</html>
S