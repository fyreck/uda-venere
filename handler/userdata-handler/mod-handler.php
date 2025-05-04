<?php
    require "../conn.php";
    require "../auth.php";

    $q = "UPDATE UTENTE SET TipoUtente = 'MOD' WHERE NomeUtente = ?";
    $s = $conn->prepare($q);
    $s->bind_param("s", $user);

    // echo $user;
    if($s->execute()){
        header("Location: ../../area_personale.php");
        exit();
    }
    else{
        echo "errore";
    }
    
?>  