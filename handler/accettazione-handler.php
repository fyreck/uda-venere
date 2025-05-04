<?php
    require "./conn.php";
    require "./auth.php";

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $evento = $_POST['evento'];
        $stato = 'ACCETTATO';

        $q = "UPDATE EVENTO SET Stato = ? WHERE IDEvento = ?";
        $s = $conn->prepare($q);
        $s->bind_param("si", $stato, $evento);
        if($s->execute()){
            header("Location: ../dashboard.php");
        }
    }
?>  