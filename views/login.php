<?php
namespace asc;
session_start();
if (isset($_SESSION['username'])) {
    header('location: principal.php');
}

require_once '../controller/database/MySQL_DataMapper.php';

try {
    $mapper = MySQL_DataMapper::getInstance();

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $validarlogin = $mapper->fetchUserByUsername($_POST['username']);
        if($validarlogin != false && password_verify($_POST['password'], $validarlogin['password']))
        {
            $_SESSION['username'] = $validarlogin['username'];
            $_SESSION['fname'] = $validarlogin['fname'];
            $_SESSION['lname'] = $validarlogin['lname'];
            $_SESSION['role'] = $validarlogin['role'];
            header("Location: ../index.php");
        }
        else
        {
            echo "<script>alert('Usuarios ou Senha Incorretos!');</script>";
        }
    }
} catch (\Exception $e) {
    print_r($e->getTrace());
    echo $e->getMessage(), "\n";
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ASC | Login</title>

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

<body class="gray-bg">

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <img src="../images/STM.png" alt="STM" width="100%">
        <h3>Bem-vindo  ao ASC</h3>
        <form id="login-form" class="m-t" role="form" action="" method="post">
            <div class="form-group">
                <input name="username" type="text" class="form-control" placeholder="Usuário" required="">
            </div>
            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="Senha" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Entrar</button>

            <a href="#"><small>Esqueceu sua senha?</small></a>
        </form>
    </div>
</div>

<!-- Mainly scripts -->
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.js"></script>

<script>
    $('#login-form input[type=text],#login-form input[type=password]').on('change invalid', function() {
        var textfield = $(this).get(0);

        textfield.setCustomValidity('');

        if (!textfield.validity.valid) {
            textfield.setCustomValidity('Por favor, preencha este campo.');
        }
    });
</script>

</body>

</html>
