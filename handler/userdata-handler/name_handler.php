<?php
    require "../conn.php";
    require "../auth.php";

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $user = $_SESSION['user'];
        $nome = htmlentities($_POST['nome']);
        
        $query = "UPDATE UTENTE SET Nome = ? WHERE NomeUtente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $nome, $user);
        if($stmt->execute()){
            header("Location: ../../area_personale.php");
            exit();
        }
    }
?>