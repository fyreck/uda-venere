<?php
session_start();

// Inizializza $_SESSION['error'] se non esiste già
if (!isset($_SESSION['error'])) {
    $_SESSION['error'] = null;
}

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

require "./conn.php";

if ($conn->connect_error) {
    die("Errore di connessione al database: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $evento = htmlentities($_POST['Evento']);
    $utente = $_SESSION['user'];

    $queryVerifica = "SELECT * FROM PRENOTAZIONE WHERE Evento = ? AND Utente = ?";
    $stmtVerifica = $conn->prepare($queryVerifica);
    if (!$stmtVerifica) {
        die("Errore nella preparazione della query di verifica: " . $conn->error);
    }
    $stmtVerifica->bind_param("is", $evento, $utente);
    $stmtVerifica->execute();
    $stmtVerifica->store_result();

    if ($stmtVerifica->num_rows > 0) {
        $_SESSION['error'] = "Hai già prenotato questo evento!";
        header("Location: ../eventi_personali.php");
        exit();
    }
    $stmtVerifica->close();

    $queryInserimento = "INSERT INTO PRENOTAZIONE VALUES (?,?)";
    $stmtInserimento = $conn->prepare($queryInserimento);
    if (!$stmtInserimento) {
        die("Errore nella preparazione della query di inserimento: " . $conn->error);
    }
    $stmtInserimento->bind_param("is", $evento, $utente);

    if ($stmtInserimento->execute()) {
        header("Location: ../eventi_personali.php");
    } else {
        $_SESSION['error'] = "Errore nell'inserimento della prenotazione: " . $stmtInserimento->error;
        header("Location: ../eventi_personali.php");
    }

    $stmtInserimento->close();
}

?>