<?php
    require "../conn.php";
    require "../auth.php";

    $preUser = $_SESSION['user'];

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $username = htmlentities($_POST['username']);

        $query = "UPDATE UTENTE SET NomeUtente = ? WHERE NomeUtente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $username, $preUser);
        if($stmt->execute()){
            $_SESSION['user']=$username;
            header("Location: ../../area_personale.php");
            exit();
        }
    }
?>