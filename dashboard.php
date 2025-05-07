<?php
    require "./handler/conn.php";   
    require "./handler/auth.php";

    if($conn->connect_errno){
        $_SESSION['error_page']='conn';

        // TODO: error_page.php di landing con visualizzazione errore

        header("Location: error_page.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UDA Venere</title>
    <link rel="stylesheet" href="./src/plainstyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
	<link rel="icon" href="./images/favicon.png" type="image/x-icon">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ephesis&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet"> 
</head>
<body>

    <div class="container">
        
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

        <div class="spacer"></div>

        <nav style="display: <?= $loggato ? 'block' : 'none' ?> ">
            <div class="btn-container">
                <button class="btn-nav btn-dash focus">
                    <a href="./dashboard.php">
                        <i class="fa-solid fa-list-ul fa-2xl"></i>
                    </a>
                </button>
                <div class="btn-else">
                    <button class="btn-nav btn-tickets">
                        <a href="./eventi_personali.php">
                            <i class="fa-solid fa-ticket fa-2xl"></i>
                        </a>
                    </button>
                    <button class="btn-nav btn-personal">
                        <a href="./area_personale.php">
                            <i class="fa-solid fa-user fa-2xl"></i>
                        </a>
                    </button>
                    <button class="btn-nav btn-users" style="display: <?= $mod ? 'block' : 'none' ?>;">
                        <a href="./gestione.php">
                            <i class="fa-solid fa-gear fa-2xl"></i>
                        </a>
                    </button>
                </div>
            </div>
        </nav>


        <div class="cards-wrapper" style="margin-left: <?= $loggato ? '64px' : '0px' ?>">
            <div class="cards">
                <?php
                    $sql = "SELECT E.IDEvento, E.Titolo, E.DataEvento, E.OraEvento, E.Luogo, C.Nome as 'NomeCategoria', E.Descrizione, E.Immagine, E.Prezzo, E.NumeroPosti, A.Nome, A.Cognome 
                            FROM EVENTO as E
                            JOIN PARTECIPAZIONE as P ON E.IDEvento = P.Evento
                            JOIN ARTISTA as A ON A.IDArtista = P.Artista
                            JOIN CATEGORIAINTERESSE as C ON E.Categoria = C.IDCategoria
                            WHERE E.DataEvento > ? AND E.Stato = ? AND E.NumeroPosti > 0
                            ORDER BY E.IDEvento";

                    $oggi = date('Y-m-d');
                    
                    $stato = "ACCETTATO";

                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $oggi, $stato);
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
                                
                                <form action="./handler/prenotazione_handler.php" method="POST">
                                    <input type="hidden" name="Evento" value="<?= $row['IDEvento'] ?>">
                                    <input type="hidden" name="Utente" value="<?php $user ?>">

                                    <button type="submit" style="display: <?= $loggato ? 'block' : 'none' ?>" class="book-event-btn">Prenota</button>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endwhile; } ?>
            </div>
        </div>

        <div class="add-evento" style="display: flex; margin-top: 60px; cursor: pointer;" id="idplus" onclick="openCard('idplus')">
            <div class="btn-add-evento" style="margin: auto; background-color: transparent; display: <?= $loggato ? 'block' : 'none' ?>">
                <i class="fa-solid fa-circle-plus fa-2xl" style="background-color: transparent; font-size: 70px; color: #DFAB44;"></i>
            </div>                                       
        </div>

        <div class="card-modal-overlay" id="modal-idplus">
            <div id="card-modal-content">
                <button class="card-modal-close" onclick="closeCard('idplus')">×</button>
                <div class="card-enlarged">
                    <h3>INSERISCI IL TUO EVENTO</h3>
                    
                    <div class="wrapper">
                    <form action="./handler/inserimento_handler.php" method="POST" enctype="multipart/form-data">
                                <div class="input-field">
                                    <input type="text" name="titolo" required>
                                    <label for="titolo">Inserire titolo evento</label>
                                </div>
                                <div class="input-field">
                                    <input type="text" name="luogo" placeholder="Edificio, Città" required>
                                    <label for="luogo evento">Inserire luogo evento</label>
                                </div>
                                <div class="input-field">
                                    <label for="data evento">Inserire data evento</label><br><br>
                                    <input type="date" name="data" required>
                                </div>
                                <div class="input-field">
                                    <label for="ora evento">Inserire ora evento</label><br><br>
                                    <input type="time" name="ora" required>
                                </div>
                                <div class="input-field">
                                    <input type="number" name="posti" required>
                                    <label for="posti">Inserire numero posti disponibili</label>
                                </div>
                                <div class="input-field">
                                    <input type="number" name="prezzo" required>
                                    <label for="prezzo evento">Inserire prezzo evento</label>
                                </div>
                                <div class="input-field">
                                    <label for="cetegoria">Scegliere categoria</label><br><br>
                                    <select name="categoria">
                                        <?php
                                            $query = "SELECT * FROM CATEGORIAINTERESSE";
                                            $result = $conn->query($query);
                                            if ($result->num_rows > 0) :
                                                while ($row = $result->fetch_assoc()):?>
                                                    <option value="<?= $row['Nome'] ?>"><?= $row['Nome'] ?></option>
                                                <?php endwhile;
                                            endif;
                                        ?>
                                    </select>
                                    <label for="cetegoria">Scegliere categoria</label>
                                </div>
                                <div class="input-field">
                                    <textarea name="descrizione">Inserire descrizione</textarea>
                                    <label for="descrizione"></label>
                                </div>
                                <div class="input-field">
                                    <input type="file" name="immagine" accept="image/png, image/jpg, image/jpeg">
                                </div>
                                <button type="submit">INSERISCI</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>

    </div> 
    <script src="./src/cards.js"></script>
</body>
