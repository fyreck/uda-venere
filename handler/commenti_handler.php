<?php
    require "./conn.php";
    require "./auth.php";

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $voto = htmlentities($_POST['voto']);
        $descrizione = htmlentities($_POST['descrizione']);
        $evento = $_POST['evento'];

        $query = "INSERT INTO COMMENTO VALUES (?,?,?,?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("issi", $evento, $user, $descrizione, $voto);
        
        if($stmt->execute()){
            header("Location: ../eventi_personali.php#eventi-passati");
            exit;
        }
        else{
            $_SESSION['error'] = "Errore nell'inserimento di un commento";
            echo "Errore nell'inserimento di un commento";
        }

    }
?>