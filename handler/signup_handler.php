<?php
    session_start();

    require "conn.php";

    $_SESSION['error'] = null;
    $_SESSION['success'] = null;

    $username = trim($_POST['username']);
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    $email = trim($_POST['mail']);
    $password = $_POST['pw'];
    $categorie = $_POST['categorie'] ?? [];
    $newsletter = $_POST['newsletter'];
    if(isset($_POST['newsletter'])){ $newsletter = 'SI';}
    else{ $newsletter = 'NO'; }

    if (empty($categorie)) {
        $_SESSION['error'] = "Devi selezionare almeno una categoria di interesse.";
        header("Location: ../signup.php");
        exit;
    }

    $check = $conn->prepare("SELECT 1 FROM UTENTE WHERE NomeUtente = ? OR Email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Username o email già registrati.";
        header("Location: ../signup.php");
        exit;
    }
    $check->close();

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmtUtente = $conn->prepare("INSERT INTO UTENTE (NomeUtente, Email, Password, Nome, Cognome, TipoUtente, Newsletter) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmtUtente) {
        $_SESSION['error'] = "Errore nella preparazione della query utente: " . $conn->error;
        header("Location: ../signup.php");
        exit;
    }
    $tipo = 'USER';
    $stmtUtente->bind_param("sssssss", $username, $email, $passwordHash, $nome, $cognome, $tipo, $newsletter);
    if (!$stmtUtente->execute()) {
        $_SESSION['error'] = "Errore nell'inserimento dell'utente: " . $stmtUtente->error;
        header("Location: ../signup.php");
        exit;
    }else{

      foreach($categorie as $c){
          $q = "INSERT INTO PREFERENZA VALUES (?,?)";
          $s = $conn->prepare($q);
          $s->bind_param("si", $username, $c);
          if(!$s->execute()){
              $_SESSION['error'] = "Errore binding cateogrie-utente: ". $conn->error;
              header("Location: ../signup.php");
              exit();
          }
      }
   }



    if (empty($_SESSION['error'])) {
        $_SESSION['success'] = "Registrazione completata con successo!";
    }

    $conn->close();
    header("Location: ../login.php");
    exit;
?>
