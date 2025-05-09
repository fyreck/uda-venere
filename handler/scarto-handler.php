<?php
    require "./conn.php";
    require "./auth.php";

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $evento = $_POST['evento'];

        $q = "DELETE FROM EVENTO WHERE IDEvento = ?";
        $s = $conn->prepare($q);
        $s->bind_param("i", $evento);
        if($s->execute()){
            header("Location: ../eventi_personali.php");
        }
    }
?>  