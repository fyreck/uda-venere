<?php
    require "../conn.php";
    require "../auth.php";

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $user = $_SESSION['user'];

        $q = "UPDATE UTENTE SET Newsletter='SI' WHERE NomeUtente = ?";
        $s = $conn->prepare($q);
        $s->bind_param("s", $utente);
        if($s->execute()){
            header("Location: ../../area_personale.php");
        }
    }
?>