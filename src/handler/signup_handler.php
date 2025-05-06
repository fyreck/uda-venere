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

    $stmtUtente = $conn->prepare("INSERT INTO UTENTE (NomeUtente, Email, Password, Nome, Cognome) VALUES (?, ?, ?, ?, ?)");
    if (!$stmtUtente) {
        $_SESSION['error'] = "Errore nella preparazione della query utente: " . $conn->error;
        header("Location: ../signup.php");
        exit;
    }
    $stmtUtente->bind_param("sssss", $username, $email, $passwordHash, $nome, $cognome);
    if (!$stmtUtente->execute()) {
        $_SESSION['error'] = "Errore nell'inserimento dell'utente: " . $stmtUtente->error;
        header("Location: ../signup.php");
        exit;
    }
    $stmtUtente->close();

    $stmtGetId = $conn->prepare("SELECT IDCategoria FROM CategoriaInteresse WHERE Nome = ?");
    $stmtInsertPref = $conn->prepare("INSERT INTO PREFERENZA (Utente, Categoria) VALUES (?, ?)");

    foreach ($categorie as $catNome) {
        $stmtGetId->bind_param("s", $catNome);
        $stmtGetId->execute();

        $stmtGetId->store_result();

        $stmtGetId->bind_result($idcat);
        if ($stmtGetId->fetch()) {
            $stmtInsertPref->bind_param("si", $username, $idcat);
            if (!$stmtInsertPref->execute()) {
                $_SESSION['error'] = "Errore nell'aggiunta della preferenza '$catNome': " . $stmtInsertPref->error;
            }
        }
        $stmtGetId->free_result(); 
    }


    $stmtGetId->close();
    $stmtInsertPref->close();

    if (empty($_SESSION['error'])) {
        $_SESSION['success'] = "Registrazione completata con successo!";
    }

    $conn->close();
    header("Location: ../login.php");
    exit;
?>
