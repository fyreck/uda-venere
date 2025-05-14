<?php
    require "./handler/conn.php";
    require "./handler/auth.php";
    

    if(!$loggato){
        header("Location: ./login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventi Personali</title>
    <link rel="stylesheet" href="./src/plainstyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="icon" href="./images/favicon.png" type="image/x-icon">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ephesis&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 
</head>
<body>
    <header>
        <h1><a href="./index.html">VenUS</a></h1>
        <div class="button" style="display: <?= $loggato ? 'none' : 'block' ?> ">
            <a href="./login.php">Accedi</a>
            <a href="./signup.php">Registrati</a>
        </div>
        <div class="button user" style="display: <?= $loggato ? 'block' : 'none' ?> ">
            <span>Benvenuto <?= $user ?></span>
        </div>
    </header>

    <nav style="display: <?= $loggato ? 'block' : 'none' ?> ">
        <div class="btn-container">
            <a href="./dashboard.php" class="btn-nav">
                	<button class="btn-nav btn-dash">
                        <i class="fa-solid fa-list-ul fa-2xl"></i>
                	</button>
              	</a>
                	<a href="./eventi_personali.php" class="btn-nav">
                    	<button class="btn-nav btn-tickets focus">
                            <i class="fa-solid fa-ticket fa-2xl"></i>
                        </button>
                    </a>
                    <a href="./area_personale.php" class="btn-nav">
                    	<button class="btn-nav btn-personal">
                            <i class="fa-solid fa-user fa-2xl"></i>
                        </button>
                  	</a>
                    <a href="./gestione.php" class="btn-nav">
                    	<button class="btn-nav btn-users" style="display: <?= $owner ? 'block' : 'none' ?>;">    
                            <i class="fa-solid fa-gear fa-2xl"></i>
                        </button> 
                   	</a>
        </div>
    </nav>

    <div class="spacer"></div>

    <div class="wrapper-eventi">
        <section class="eventi eventi-prenotati">
            <h2 class="titolo-eventi" style="text-align: center;">EVENTI PRENOTATI</h2>

            <section class="eventi eventi-futuri" id="eventi-futuri">    
                <h3 class="titolo-eventi" style="text-align: center;" >EVENTI FUTURI</h3>
                <div class="cards-wrapper" style="margin-left: <?= $loggato ? '64px' : '0px' ?>">
                    <div class="cards">
                        <?php
                            $sql = "SELECT E.IDEvento, E.Titolo, E.DataEvento, E.OraEvento, E.Luogo, C.Nome as 'NomeCategoria', E.Descrizione, E.Immagine, E.Prezzo, E.NumeroPosti, A.Nome, A.Cognome FROM EVENTO as E, ARTISTA as A, CATEGORIAINTERESSE as C, PARTECIPAZIONE as P, UTENTE as U, PRENOTAZIONE as PR WHERE E.IDEvento = P.Evento AND A.IDArtista = P.Artista AND C.IDCategoria = E.Categoria AND PR.Utente = ? AND E.IDEvento = PR.Evento AND E.DataEvento > ? GROUP BY E.IDEvento";

                            $oggi = date('Y-m-d');

                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ss", $user, $oggi);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo "<span class='out'>Nessun Evento Presente</span>";
                            }else{
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
                                        } else { 
                                    ?>
                                    <span>NESSUN COMMENTO</span>
                                    <?php 
                                        }
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

                        <?php 
                                endwhile; 
                            } 
                        ?>
                    </div>
                </div>
            </section>

            <section class="eventi eventi-passati" id="eventi-passati">
                <h3 class="titolo-eventi" style="text-align: center;" >EVENTI PASSATI</h3>
                <div class="cards-wrapper" style="margin-left: <?= $loggato ? '64px' : '0px' ?>">
                    <div class="cards">
                        <?php
                            $sql = "SELECT E.IDEvento, E.Titolo, E.DataEvento, E.OraEvento, E.Luogo, C.Nome as 'NomeCategoria', E.Descrizione, E.Immagine, E.Immagine, E.Prezzo, E.NumeroPosti, A.Nome, A.Cognome FROM EVENTO as E, ARTISTA as A, CATEGORIAINTERESSE as C, PARTECIPAZIONE as P, UTENTE as U, PRENOTAZIONE as PR WHERE E.IDEvento = P.Evento AND A.IDArtista = P.Artista AND C.IDCategoria = E.Categoria AND U.NomeUtente = ? AND E.IDEvento = PR.Evento AND E.DataEvento < ? GROUP BY E.IDEvento";

                            $oggi = date('Y-m-d');

                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ss", $user, $oggi);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo "<span class='out'>Nessun Evento Presente</span>";
                            }else{
                                while ($row = $result->fetch_assoc()):
                                    $data = $row['DataEvento'];
                                    $ora = $row['OraEvento'];

                                    $dataFormattata = date('d/m/Y', strtotime($data));
                                    $oraFormattata = date('H:i', strtotime($ora));

                                    $data_ora = $dataFormattata . ", " . $oraFormattata;
                        ?>
                        <div class="card card-passate" id="<?= $row['IDEvento'] ?>" onclick="openCard(<?= $row['IDEvento'] ?>)">
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
                                        } else { 
                                    ?>
                                    <span>NESSUN COMMENTO</span>
                                    <?php 
                                        }
                                    ?>
                                </section>
                            </div>
                        </div>

                        <div class="card-modal-overlay" id="modal-<?= $row['IDEvento'] ?>">
                            <div id="card-modal-content">
                                <button class="card-modal-close" onclick="closeCard(<?= $row['IDEvento'] ?>); closeComments()">×</button>
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

                                        <form action="./handler/commenti_handler.php" method="POST" id="form-comm" style="display: none;">
                                            <input type="hidden" name="evento" value="<?= $row['IDEvento'] ?>">

                                            <div class="input-field-comm">
                                                <select name="voto" required>
                                                    <option value="0">0</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                                <label for="voto" style="color: black;">Selezionare valutazione</label>
                                            </div>

                                            <div class="input-field-comm">
                                                <textarea name="descrizione"></textarea>
                                                <label for="descrizione" style="color: black;">Inserire descrizione</label>
                                            </div>

                                            <button type="submit" class="book-event-btn">COMMENTA</button>
                                        </form>

                                        <?php
                                            $comm = true;

                                            $qc = "SELECT * FROM COMMENTO WHERE Utente = ?";
                                            $sc = $conn->prepare($qc);
                                            $sc->bind_param("s", $user);
                                            $sc->execute();
                                            $rc = $sc->get_result();

                                            if($rc->num_rows>0){
                                                $comm = false;
                                            }
                                            
                                        ?>
                                        <button id="btn-comm"  class="book-event-btn" onclick="showComments()" style="display: <?= $comm ? 'block' : 'none' ?>;">COMMENTA</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                                endwhile; 
                            } 
                        ?>
                    </div>
                </div>
            </section>
        </section>

        <section class="eventi-personali">
            <h2 class="titolo-eventi" style="text-align: center;">EVENTI PERSONALI</h2>

            <section class="eventi eventi-accettati" id="eventi-accettati">
                <h3 class="titolo-eventi" style="text-align: center; display: <?= $mod ? 'none' : 'block' ?>;">EVENTI ACCETTATI</h3>
                <div class="cards-wrapper" style="margin-left: <?= $loggato ? '64px' : '0px' ?>">
                    <div class="cards">
                        <?php
                            $queryIDArtista = "SELECT IDArtista from ARTISTA WHERE Nome = ? AND Cognome = ?";

                            $stmtIDArtista = $conn->prepare($queryIDArtista);
                            $stmtIDArtista->bind_param("ss", $nomeUtente, $cognomeUtente);
                            $stmtIDArtista->execute();
                            $resultIDArtista = $stmtIDArtista->get_result();

                            if($resultIDArtista->num_rows > 0){
                                $rowArtista = $resultIDArtista->fetch_assoc();
                                $IDArtista = $rowArtista['IDArtista'];
                            }
                            else{
                                $qAll = "SELECT * FROM ARTISTA";
                                $rAll = $conn->query($qAll);
                                $proxArtista = $rAll->num_rows+1;
                                $q = "INSERT INTO ARTISTA VALUES (?,?,?)";
                                $s = $conn->prepare($q);
                                $s->bind_param("iss", $proxArtista, $nomeUtente, $cognomeUtente);
                                $s->execute();
                                $IDArtista=$proxArtista;
                            }

                            $sql = "SELECT E.IDEvento, E.Titolo, E.DataEvento, E.OraEvento, E.Luogo, C.Nome as 'NomeCategoria', E.Descrizione, E.Immagine, E.Immagine, E.Prezzo, E.NumeroPosti FROM EVENTO as E, PARTECIPAZIONE as P, CATEGORIAINTERESSE as C WHERE E.Categoria = C.IDCategoria AND E.IDEvento = P.Evento AND P.Artista = ? AND STATO = ?";
                            
                            $stato = 'ACCETTATO';
                            
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ss", $IDArtista, $stato);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo "<span class='out'>Nessun Evento Presente</span>";
                            }else{
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
                                <img src="<?= $row['Immagine'] ?>" alt="immagine" class="card-image">
                            </div>
                            <div class="card-info">
                                <div class="riga">
                                    <i class="fa-solid fa-user fa-lg"></i><span><?= $nomeUtente ?> <?= $cognomeUtente ?></span><br>
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
                                        } else { 
                                    ?>
                                    <span>NESSUN COMMENTO</span>
                                    <?php 
                                        }
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
                                            <i class="fa-solid fa-user fa-lg"></i><span><?= $nomeUtente ?> <?= $cognomeUtente ?></span><br>
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

                        <?php 
                                endwhile; 
                            } 
                        ?>
                    </div>
                </div>
            </section>

            <section class="eventi eventi-in-attesa" id="eventi-in-attesa" style="display: <?= $mod ? 'none' : 'block' ?>;">
            <h3 class="titolo-eventi" style="text-align: center;" >EVENTI IN ATTESA</h3>
                <div class="cards-wrapper" style="margin-left: <?= $loggato ? '64px' : '0px' ?>">
                    <div class="cards">
                        <?php
                            $queryIDArtista = "SELECT IDArtista from ARTISTA WHERE Nome = ? AND Cognome = ?";

                            $stmtIDArtista = $conn->prepare($queryIDArtista);
                            $stmtIDArtista->bind_param("ss", $nomeUtente, $cognomeUtente);
                            $stmtIDArtista->execute();
                            $resultIDArtista = $stmtIDArtista->get_result();
                            $rowIDArtista = $resultIDArtista->fetch_assoc();

                            $IDArtista = $rowIDArtista['IDArtista'];


                            $sql = "SELECT E.IDEvento, E.Titolo, E.DataEvento, E.OraEvento, E.Luogo, C.Nome as 'NomeCategoria', E.Descrizione, E.Immagine, E.Immagine, E.Prezzo, E.NumeroPosti FROM EVENTO as E, PARTECIPAZIONE as P, CATEGORIAINTERESSE as C WHERE E.Categoria = C.IDCategoria AND E.IDEvento = P.Evento AND P.Artista = ? AND STATO = ?";
                            
                            $stato = 'IN ATTESA';
                            
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ss", $IDArtista, $stato);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo "<span class='out'>Nessun Evento Presente</span>";
                            }else{
                                while ($row = $result->fetch_assoc()):
                                    $data = $row['DataEvento'];
                                    $ora = $row['OraEvento'];

                                    $dataFormattata = date('d/m/Y', strtotime($data));
                                    $oraFormattata = date('H:i', strtotime($ora));

                                    $data_ora = $dataFormattata . ", " . $oraFormattata;
                        ?>
                        <div class="card card-in-attesa" id="<?= $row['IDEvento'] ?>" onclick="openCard(<?= $row['IDEvento'] ?>)">
                            <h2 class="card-title"><?= $row['Titolo'] ?></h2>
                            <div class="img-contenitore">
                                <img src="<?= $row['Immagine'] ?>" alt="immagine" class="card-image">
                            </div>
                            <div class="card-info">
                                <div class="riga">
                                    <i class="fa-solid fa-user fa-lg"></i><span><?= $nomeUtente ?> <?= $cognomeUtente ?></span><br>
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
                                        } else { 
                                    ?>
                                    <span>NESSUN COMMENTO</span>
                                    <?php 
                                        }
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

                        <?php 
                                endwhile; 
                            } 
                        ?>
                    </div>
                </div>
            </section>
        </section>

        <section class="eventi-mod">
            <section class="eventi eventi-da-accettare" id="eventi-da-accettare" style="display: <?= $mod ? 'block' : 'none' ?>;">
                <h2 class="titolo-eventi" style="text-align: center;">EVENTI DA ACCETTARE</h2>
                <div class="cards-wrapper" style="margin-left: <?= $loggato ? '64px' : '0px' ?>">
                    <div class="cards">
                        <?php
                            $sql = "SELECT E.IDEvento, E.Titolo, E.DataEvento, E.OraEvento, E.Luogo, C.Nome as 'NomeCategoria', E.Descrizione, E.Immagine, E.Immagine, E.Prezzo, E.NumeroPosti, A.Nome, A.Cognome FROM EVENTO as E, PARTECIPAZIONE as P, CATEGORIAINTERESSE as C, ARTISTA as A WHERE E.Categoria = C.IDCategoria AND E.IDEvento = P.Evento AND A.IDArtista = P.Artista AND E.Stato = ? ORDER BY 1";

                            $stato = 'IN ATTESA';

                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("s",  $stato);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows === 0) {
                                echo "<span class='out'>Nessun Evento Presente</span>";
                            }else{
                                while ($row = $result->fetch_assoc()):
                                    $data = $row['DataEvento'];
                                    $ora = $row['OraEvento'];

                                    $dataFormattata = date('d/m/Y', strtotime($data));
                                    $oraFormattata = date('H:i', strtotime($ora));

                                    $data_ora = $dataFormattata . ", " . $oraFormattata;
                        ?>
                        <div class="card card-in-attesa" id="<?= $row['IDEvento'] ?>" onclick="openCardMod(<?= $row['IDEvento'] ?>)">
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
                                        } else { 
                                    ?>
                                    <span>NESSUN COMMENTO</span>
                                    <?php 
                                        }
                                    ?>
                                </section>
                            </div>
                        </div>

                        <div class="card-modal-overlay" id="modal-mod-<?= $row['IDEvento'] ?>"> 
                            <div id="card-modal-content">
                                <button class="card-modal-close" onclick="closeCardMod(<?= $row['IDEvento'] ?>); closeComments()">×</button>
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

                                        <form action="./handler/accettazione-handler.php" method="POST">
                                            <input type="hidden" name="evento" value="<?= $row['IDEvento'] ?>">
                                            <button type="submit" class="btn-accetazione">ACCETTA</button>
                                        </form>
                                        <form action="./handler/scarto-handler.php" method="POST">
                                            <input type="hidden" name="evento" value="<?= $row['IDEvento'] ?>">
                                            <button type="submit" class="btn-accetazione">SCARTA</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                                endwhile; 
                            } 
                        ?>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <script src="./src/cards.js"></script>

    <script>
    	function openCardMod(eventId) {
            document.getElementById('modal-mod-' + eventId).style.display = 'flex';
        }
        function closeCardMod(eventId) {
            document.getElementById('modal-mod-' + eventId).style.display = 'none';
        }

        function showComments(){
            document.getElementById('form-comm').style.display="block";
            document.getElementById('btn-comm').style.display="none";
        }

        function closeComments(){
            document.getElementById('form-comm').style.display="none";
            document.getElementById('btn-comm').style.display="block";
        }
    </script>
</body>