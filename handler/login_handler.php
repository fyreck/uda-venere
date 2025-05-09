<?php
    require "./conn.php";
    require "./auth.php";

    unset($_SESSION['user']);
    header("Location: ../login.php");
    exit();
?>