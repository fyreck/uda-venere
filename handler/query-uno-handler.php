<?php
    require "./conn.php";   
    require "./auth.php";

    if($conn->connect_errno){
        $_SESSION['error']='conn';
    }
    
    if(!$loggato && !$owner){
    	header("Location: ../login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UDA Venere</title>
    <link rel="stylesheet" href="../src/plainstyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="icon" href="./images/favicon.png" type="image/x-icon">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ephesis&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 
</head>
<body>

    <div class="container">
        
        <header>
            <h1><a href="../index.html">VenUS</a></h1>
            <div class="button" style="display: <?= $loggato ? 'none' : 'block' ?> ">
                <a href="./login.php">Accedi</a>
                <a href="./signup.php">Registrati</a>
            </div>
            <div class="button user" style="display: <?= $loggato ? 'block' : 'none' ?> ">
                <span>Benvenuto <?= $user ?></span>
            </div>
        </header>

        <div class="spacer"></div>

        <nav style="display: <?= $loggato ? 'block' : 'none' ?> ">
            <div class="btn-container">
                <a href="../dashboard.php">
                    <button class="btn-nav btn-dash">
                    
                        <i class="fa-solid fa-list-ul fa-2xl"></i>
                    
                    </button>
                </a>
                <a href="../eventi_personali.php" class="btn-nav">
                    <button class="btn-nav btn-tickets">
                        <i class="fa-solid fa-ticket fa-2xl"></i>
                    </button>
                </a>
                <a href="../area_personale.php" class="btn-nav">
                    <button class="btn-nav btn-personal">
                        <i class="fa-solid fa-user fa-2xl"></i>
                    </button>
                </a>
                <a href="../gestione.php" class="btn-nav">
                    <button class="btn-nav btn-users focus" style="display: <?= $owner ? 'block' : 'none' ?>;">    
                        <i class="fa-solid fa-gear fa-2xl"></i>
                    </button> 
                </a>
            </div>
        </nav>
		<div class="wrapper wrapper-query" style="width: 80%;">
          <h2 class="titolo-eventi">GESTIONE QUERY</h2>
          <h3>PAGINA DI VISUALIZZAZIONE QUERY A SCOPO DIDATTICO*</h3><br><br>

          <h3>I titoli e le date delle esposizioni tematiche che si sono tenute nel periodo 1 gennaio – 31
              dicembre di un determinato anno</h3>


          <div class="cards-wrapper">
              <div class="cards">
                  <?php

                      if($_SERVER['REQUEST_METHOD']=='POST'){
                          $anno = $_POST['anni'];
                          $inizio = $anno . "-01-01";
                          $fine = $anno . "-12-31";
                          //echo $inizio . " - " . $fine;

                          $sql = "SELECT E.IDEvento, E.Titolo, E.DataEvento, E.OraEvento, E.Luogo, C.Nome as 'NomeCategoria', E.Descrizione, E.Immagine, E.Prezzo, E.NumeroPosti, A.Nome, A.Cognome 
                                  FROM EVENTO as E
                                  JOIN PARTECIPAZIONE as P ON E.IDEvento = P.Evento
                                  JOIN ARTISTA as A ON A.IDArtista = P.Artista
                                  JOIN CATEGORIAINTERESSE as C ON E.Categoria = C.IDCategoria
                                  WHERE E.DataEvento > ? AND E.DataEvento < ? AND E.Stato = ? AND E.NumeroPosti > 0
                                  ORDER BY E.IDEvento";

                          $stato = "ACCETTATO";

                          $stmt = $conn->prepare($sql);
                          $stmt->bind_param("sss", $inizio, $fine, $stato);
                          $stmt->execute();
                          $result = $stmt->get_result();

                          if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()):
                                  $data = $row['DataEvento'];
                                  $ora = $row['OraEvento'];

                                  $dataFormattata = date('d/m/Y', strtotime($data));
                                  $oraFormattata = date('H:i', strtotime($ora));

                                  $data_ora = $dataFormattata . ", " . $oraFormattata;

                  ?>
                      <div class="card" id="<?= $row['IDEvento'] ?>" onclick="openCard(<?= $row['IDEvento'] ?>)">
                          <h2 class="card-title"><?= $row['Titolo'] ?></h2>
                          <div class="img-contenitore">
                              <img src="../<?= $row['Immagine'] ?>" alt="immagine" class="card-image">
                          </div>
                          <div class="card-info">
                              <div class="riga">
                                  <i class="fa-solid fa-user fa-lg"></i><span><?= $row['Nome'] ?> <?= $row['Cognome'] ?></span><br>
                              </div>
                              <div class="riga">
                                  <i class="fa-solid fa-location-dot fa-lg"></i><span><?= $row['Luogo'] ?></span><br>
                              </div>
                              <div class="riga">
                                  <i class="fa-solid fa-clock fa-lg"></i><span><?= $data_ora ?></span><br>
                              </div>
                              <div class="riga">
                                  <i class="fa-solid fa-ticket fa-lg"></i><span><?= $row['NumeroPosti'] ?></span><br>
                              </div>
                              <div class="riga">
                                  <i class="fa-solid fa-money-bill fa-lg"></i>
                                  <span><?php if($row['Prezzo']==0){ echo "gratuito"; }else{ echo "€ " . $row['Prezzo'];} ?>
                                  </span><br>
                              </div>
                              <div class="riga">
                                  <i class="fa-solid fa-align-left fa-lg"></i><span><?= $row['Descrizione'] ?></span><br>
                              </div>
                              <br><span class="riga-categoria"><?= $row['NomeCategoria'] ?></span>
                              <section class="commenti" style="display: none;">
                                  <?php
                                      $sql_com = "SELECT Utente, Voto, Descrizione FROM COMMENTO WHERE Evento = ?";
                                      $stmt_com = $conn->prepare($sql_com);
                                      $stmt_com->bind_param('i', $row['IDEvento']);
                                      $stmt_com->execute();
                                      $result_com = $stmt_com->get_result();

                                      if ($result_com->num_rows > 0) {
                                          while ($row_com = $result_com->fetch_assoc()): ?>
                                              <div class="commento">
                                                  <span class="user"><?= $row_com['Utente'] ?></span>
                                                  <p class="voto"><strong><?= $row_com['Voto'] ?></strong>/5</p>
                                                  <p class="descrizione"><?= $row_com['Descrizione'] ?></p>
                                              </div>
                                          <?php endwhile;
                                      } else { ?>
                                          <span>NESSUN COMMENTO</span>
                                      <?php }
                                  ?>
                              </section>
                          </div>
                      </div>

                      <div class="card-modal-overlay" id="modal-<?= $row['IDEvento'] ?>">
                          <div id="card-modal-content">
                              <button class="card-modal-close" onclick="closeCard(<?= $row['IDEvento'] ?>)">×</button>
                              <div class="card-enlarged">
                                  <h2 class="card-title"><?= $row['Titolo'] ?></h2>
                                  <div class="img-contenitore">
                                      <img src="<?= $row['Immagine'] ?>" alt="immagine" class="card-image">
                                  </div>
                                  <div class="card-info">
                                      <div class="riga">
                                          <i class="fa-solid fa-user fa-lg"></i><span><?= $row['Nome'] ?> <?= $row['Cognome'] ?></span><br>
                                      </div>
                                      <div class="riga">
                                          <i class="fa-solid fa-location-dot fa-lg"></i><span><?= $row['Luogo'] ?></span><br>
                                      </div>
                                      <div class="riga">
                                          <i class="fa-solid fa-clock fa-lg"></i><span><?= $data_ora ?></span><br>
                                      </div>
                                      <div class="riga">
                                          <i class="fa-solid fa-ticket fa-lg"></i><span><?= $row['NumeroPosti'] ?></span><br>
                                      </div>
                                      <div class="riga">
                                          <i class="fa-solid fa-money-bill fa-lg"></i>
                                          <span><?php if($row['Prezzo']==0){ echo "gratuito"; }else{ echo "€ " . $row['Prezzo'];} ?>
                                          </span><br>
                                      </div>
                                      <div class="riga">
                                          <i class="fa-solid fa-align-left fa-lg"></i><span><?= $row['Descrizione'] ?></span><br>
                                      </div>
                                      <section class="commenti" style="display: <?= $loggato ? 'block' : 'none' ?> ">
                                          <?php
                                              $sql_com = "SELECT Utente, Voto, Descrizione FROM COMMENTO WHERE Evento = ?";
                                              $stmt_com = $conn->prepare($sql_com);
                                              $stmt_com->bind_param('i', $row['IDEvento']);
                                              $stmt_com->execute();
                                              $result_com = $stmt_com->get_result();

                                              if ($result_com->num_rows > 0) {
                                                  while ($row_com = $result_com->fetch_assoc()): ?>
                                                      <div class="commento">
                                                          <span class="user"><?= $row_com['Utente'] ?></span>
                                                          <p class="voto"><strong><?= $row_com['Voto'] ?></strong>/5</p>
                                                          <p class="descrizione"><?= $row_com['Descrizione'] ?></p>
                                                      </div>
                                                  <?php endwhile;
                                              } else { ?>
                                                  <span>NESSUN COMMENTO</span>
                                              <?php }
                                          ?>
                                      </section>
                                  </div>

                              </div>
                          </div>
                      </div>

                  <?php endwhile; } }?>
              </div>
          </div>
		</div>

    </div> 
</body>
