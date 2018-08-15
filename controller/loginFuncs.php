<?php
namespace asc{

    function checkLogin() {
        if (!isset($_SESSION['username'])) {
            logout();
        }
    }

    function logout() {
        unset($_SESSION['username'], $_SESSION['fname'], $_SESSION['lname'], $_SESSION['role']);
        session_destroy();
        header('location: login.php');
    }
}