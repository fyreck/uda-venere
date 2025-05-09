<?php
    require "./conn.php";
    require "./auth.php";

    $q = "SELECT Nome, Cognome, Email FROM UTENTE WHERE NEWSLETTER = 'SI'";
    $result = $conn->query($q);

    if($result->num_rows>0){
        $rows = $result->fetch_assoc();

        foreach($rows as $row) {
            $to = $row['Email'];
            $subject = "Prenotati finché ci sono ancora posti!";
            $message = "Ciao " .$row['Nome'] . " " . $row['Cognome'] . ", avventurati nei nostri eventi e divertiti!";
            $headers = "From: no-reply@udavenere.it";

            if (mail($to, $subject, $message, $headers)) {
                echo "Email inviata con successo a $to";
            } else {
                echo "Errore nell'invio dell'email.";
            }
        }
    }
?>