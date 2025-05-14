<?php
    session_start();
    
    $loggato = false;
    $mod = false;
    $owner = false;

    if(isset($_SESSION['user'])){
        $auth = $_SESSION['user'];
        $user = $auth;

        if (filter_var($auth, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT NomeUtente FROM UTENTE WHERE Email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $auth);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($user);
            $stmt->fetch();
        }

        $loggato = true;

        $query = "SELECT TipoUtente FROM UTENTE WHERE NomeUtente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($tipo);
        $stmt->fetch();

        if($tipo === 'MOD'){
            $mod = true;
        }else{
            $mod = false;
            if($tipo === 'OWNER'){
            	$owner = true;
                $mod = true;
            }else{
            	$owner = false;
            }
        }

        $query = "SELECT Nome, Cognome FROM UTENTE WHERE NomeUtente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $nomeUtente = $row['Nome'];
        $cognomeUtente = $row['Cognome'];

        if($row['Newsletter']=='SI'){
          $iscritto = true;
        }
        else{
          $iscritto = false;
        }
        
        $timeout=180;
        
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {
            unset($_SESSION['user']);
            unset($_SESSION['LAST_ACTIVITY']);
            header("Location: ../login.php");
            exit;
        } else {
            $_SESSION['LAST_ACTIVITY'] = time(); 
        }
        
    }
?>