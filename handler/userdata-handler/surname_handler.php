<?php
    require "../conn.php";
    require "../auth.php";

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $user = $_SESSION['user'];
        $cognome = htmlentities($_POST['cognome']);
        
        $query = "UPDATE UTENTE SET Cognome = ? WHERE NomeUtente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $cognome, $user);
        if($stmt->execute()){
            header("Location: ../../area_personale.php");
            exit();
        }
    }
?>