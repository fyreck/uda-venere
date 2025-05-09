<?php
declare(strict_types=1);
header('Content-Type: application/json');
require __DIR__ . '/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['NomeUtente'])) {
    echo json_encode(['success' => false, 'message' => 'Richiesta non valida']);
    exit;
}

$NomeUtente = $_POST['NomeUtente'];

// Query unica che inverte ATTIVO⇄BANNATO
$sql = "
    UPDATE utenti
    SET Stato = CASE Stato
        WHEN 'ATTIVO' THEN 'BANNATO'
        ELSE 'ATTIVO'
    END
    WHERE NomeUtente = ?
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Errore prepare UPDATE']);
    exit;
}
$stmt->bind_param('s', $NomeUtente);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    // o utente inesistente, o già nello stesso stato
    echo json_encode(['success' => false, 'message' => 'Nessuna modifica effettuata']);
    exit;
}

// Ora prendo il nuovo stato con una SELECT rapidissima
$stmt = $conn->prepare("SELECT Stato FROM utenti WHERE NomeUtente = ?");
$stmt->bind_param('s', $NomeUtente);
$stmt->execute();
$stmt->bind_result($new_status);
$stmt->fetch();

echo json_encode([
    'success'    => true,
    'new_status' => $new_status
]);
