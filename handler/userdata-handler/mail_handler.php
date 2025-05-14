<?php
    require "../conn.php";
    require "../auth.php";

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $user = $_SESSION['user'];
        $mail = htmlentities($_POST['mail']);
        
        $query = "UPDATE UTENTE SET Email = ? WHERE NomeUtente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $mail, $user);
        if($stmt->execute()){
            header("Location: ../../area_personale.php");
            exit();
        }
    }
?>