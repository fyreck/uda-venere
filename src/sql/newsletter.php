<?php
    require "./conn.php";
    require "./auth.php"; // Assicurati che questo file NON produca output prima dell'invio delle email

    $q = "SELECT u.Nome, u.Cognome, u.Email, e.Titolo, e.Descrizione, e.DataEvento, e.OraEvento, e.Luogo FROM UTENTE u JOIN PREFERENZA p ON u.NomeUtente = p.Utente JOIN CATEGORIAINTERESSE c ON p.Categoria = c.IDCategoria JOIN EVENTO e ON e.Categoria = c.IDCategoria WHERE u.Newsletter = 'SI' AND YEARWEEK(e.DataEvento, 1) = YEARWEEK(CURDATE(), 1)";

    $result = $conn->query($q);

    if ($result->num_rows > 0) {
        // Definisci il soggetto dell'email
        $subject = "Scopri nuovi i eventi che pensiamo possano entusiasmarti!";

        // Inizia la struttura HTML dell'email (usa HEREDOC per maggiore leggibilità)
        $html_template = <<<EOT
<html>
  <head>
    <title>$subject</title>
    <style>
        :root {
            --primary-color: #CF6E0D; /* Arancione scuro */
            --secondary-color: #DFAB44; /* Giallo-arancio */
            --accent-color: #D81E5B; /* Rosa acceso */
            --primary-light: #D9D9D9; /* Grigio chiaro */
            --primary-dark: #181821; /* Quasi nero */
        }

      body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: var(--primary-dark); /* Testo scuro */
      }
      .container {
        width: 90%;
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid var(--primary-light); /* Bordo grigio chiaro */
        border-radius: 5px;
        background-color: var(--primary-light); /* Sfondo grigio chiaro */
      }
      .header {
        background-color: var(--primary-color); /* Intestazione arancione scuro */
        color: white;
        padding: 10px;
        text-align: center;
        border-radius: 5px 5px 0 0;
      }
      .content {
        padding: 20px;
      }
      .footer {
        font-size: 0.9em;
        text-align: center;
        color: #777; /* Mantenuto grigio per il footer */
        margin-top: 20px;
      }
      .button {
        display: inline-block;
        background-color: var(--accent-color); /* Bottone rosa acceso */
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 15px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Ciao [NOME] [COGNOME]!</h1>       </div>
      <div class="content">
        <p>Grandi notizie! Abbiamo nuovi eventi entusiasmanti pronti per te.</p>
        <p>
          Avventurati nei nostri eventi e divertiti! Non perdere l'occasione, i
          posti si stanno esaurendo.
        </p>
        <p><a href="../dashboard.php" class="button">Prenota Ora!</a></p>
        <hr />
      </div>
      <div class="footer">
        <p>VenUS&copy; " . date("Y") . "</p>
      </div>
    </div>
  </body>
</html>
EOT;
        // Fine HEREDOC

        // Intestazioni per l'email HTML
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: udavenere@altervista.org" . "\r\n"; 

        // Itera su ogni riga del risultato della query
        while ($row = $result->fetch_assoc()) {
            $to = $row['Email'];
            $nome = $row['Nome'];
            $cognome = $row['Cognome'];

            // Sostituisci i placeholder nell'HTML con i dati dell'utente
            $message = str_replace('[NOME]', $nome, $html_template);
            $message = str_replace('[COGNOME]', $cognome, $message);

            // Invio dell'email
            if (mail($to, $subject, $message, $headers)) {
                echo "Email inviata con successo a $to<br>";
            } else {
                echo "Errore nell'invio dell'email a $to<br>";
                // Puoi aggiungere un log degli errori qui
                // error_log("Errore invio email a $to: " . error_get_last()['message']);
            }
        }
    } else {
        echo "Nessun utente iscritto alla newsletter trovato.<br>";
    }

    // Chiudi la connessione al database
    $conn->close();
?>