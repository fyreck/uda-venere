<?php
  ob_start();

  require "./conn.php"; 
  require "./auth.php"; 

  $sql = "SELECT u.Nome, u.Cognome, u.Email, e.Titolo 
          FROM UTENTE u 
          JOIN PREFERENZA p ON u.NomeUtente = p.Utente 
          JOIN CATEGORIAINTERESSE c ON p.Categoria = c.IDCategoria 
          JOIN EVENTO e ON e.Categoria = c.IDCategoria 
          WHERE u.Newsletter = 'SI' AND YEARWEEK(e.DataEvento, 1) = YEARWEEK(CURDATE(), 1)
          ORDER BY u.Email"; // Ordinare per email facilita il raggruppamento manuale

  $result = $conn->query($sql);

  if (!$result) {
      error_log("Errore nella query SQL: " . $conn->error); 
      echo "Si è verificato un errore nel recupero dei dati. Si prega di riprovare più tardi.";
      $conn->close();
      ob_end_flush();
      exit;
  }

  $utenti_per_email = []; 

  if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          $email = $row['Email'];
          if (!isset($utenti_per_email[$email])) {
              $utenti_per_email[$email] = [
                  'Nome' => $row['Nome'],
                  'Cognome' => $row['Cognome'],
                  'Email' => $row['Email'],
                  'EventiTitoli' => [] 
              ];
          }
          $utenti_per_email[$email]['EventiTitoli'][] = htmlspecialchars($row['Titolo'], ENT_QUOTES, 'UTF-8');
      }
      $result->free(); 

      $subject_email = "Scopri nuovi eventi che pensiamo possano entusiasmarti!";
      $current_year = date("Y");

      $html_template_base = <<<EOT
          <html>
            <head>
              <title>[SUBJECT]</title>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <body style="font-family: 'Montserrat', sans-serif; line-height: 1.6; margin: 0; padding: 0;">
              <div style="width: 90%; max-width: 600px; margin: 60px auto; padding: 20px; border: 2px solid #D9D9D9; border-radius: 8px; background-color: rgba(255, 255, 255, 0.35); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                <div style="background-color: #CF6E0D; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0;">
                  <h1 style="margin: 0; font-size: 24px; color: white;">Ciao [NOME] [COGNOME]!</h1>
                </div>
                <div style="padding: 20px; text-align: left; color: #181821;">
                  <p style="margin-bottom: 15px; color: #181821;">Grandi notizie! Abbiamo nuovi eventi pronti per te, basati sulle tue preferenze.</p>
                  <p style="margin-bottom: 15px; color: #181821;">Lasciati ispirare da questi titoli e divertiti!</p>
                  <ul style="list-style-type: disc; padding-left: 20px; color: #181821;">
                    [LISTA_EVENTI_UTENTE]
                  </ul>
                  <p style="margin-bottom: 15px; color: #181821;">Premi <a href="udavenere.altervista.org/dashboard.php" style="display: inline-block; background-color: #D81E5B; color: whitesmoke !important; padding: 6px 12px; text-decoration: none; border-radius: 5px; margin-top: 15px; font-weight: bold; font-family: 'Montserrat', sans-serif;">qui</a> per scoprire di più!</p>
                  <!-- <hr style="border: none; border-top: 1px solid #D9D9D9; margin: 20px 0;" /> -->
                </div>
                <div style="font-size: 0.9em; text-align: center; color: #181821; margin-top: 20px; padding-top: 10px; border-top: 1px solid #D9D9D9;">
                  <p>VenUS &copy; [ANNO]</p>
                </div>
              </div>
            </body>
          </html>
      EOT;

      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= "From: \"VenUS Notifiche\" <udavenere@altervista.org>" . "\r\n"; 

      foreach ($utenti_per_email as $utente_info) {
          $to = $utente_info['Email'];
          $nome_utente = htmlspecialchars($utente_info['Nome'], ENT_QUOTES, 'UTF-8');
          $cognome_utente = htmlspecialchars($utente_info['Cognome'], ENT_QUOTES, 'UTF-8');

          $lista_eventi_html_specifica = "";
          if (!empty($utente_info['EventiTitoli'])) {
              foreach ($utente_info['EventiTitoli'] as $titolo_evento_sanificato) {
                  $lista_eventi_html_specifica .= "<li>" . $titolo_evento_sanificato . "</li>";
              }
          } else {
              $lista_eventi_html_specifica = "<li>Non ci sono nuovi eventi che corrispondono esattamente alle tue preferenze in programma per questa settimana. <br> Guarda se c'è qualcos'altro che ti protrebbe interessare</li>";
          }

          $message_body = str_replace('[SUBJECT]', $subject_email, $html_template_base);
          $message_body = str_replace('[NOME]', $nome_utente, $message_body);
          $message_body = str_replace('[COGNOME]', $cognome_utente, $message_body);
          $message_body = str_replace('[LISTA_EVENTI_UTENTE]', $lista_eventi_html_specifica, $message_body);
          $message_body = str_replace('[ANNO]', $current_year, $message_body);

          if (mail($to, $subject_email, $message_body, $headers)) {
              echo "Email inviata con successo a $to <br>";
          } else {
              $last_error = error_get_last(); // Recupera l'ultimo errore
              $error_message = $last_error ? $last_error['message'] : 'Nessun dettaglio errore disponibile';
              echo "Errore nell'invio dell'email a $to. Dettagli: $error_message <br>";
              error_log("Errore invio email a $to: $error_message - Oggetto: $subject_email");
          }
      }

  } else {
      echo "Nessun utente iscritto alla newsletter con eventi corrispondenti trovato per questa settimana.<br>";
  }

  $conn->close();

  ob_end_flush(); 
?>