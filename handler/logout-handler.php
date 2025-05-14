<?php
    require "./conn.php";
    require "./auth.php";

    unset($_SESSION['user']);
    unset($_SESSION['LAST_ACTIVITY']);
    header("Location: ../login.php");
    exit();
?>