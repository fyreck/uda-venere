<?php
    require "./conn.php";
    require "./auth.php";

    $q = "DELETE FROM UTENTE WHERE NomeUtente = ?";
    $s = $conn->prepare($q);
    $s->bind_param("s", $user);
    if($s->execute()){
        unset($_SESSION['user']);
        header("Location: ../signup.php");
        exit();
    }
    else{
        echo $s->errno;
    }
?>