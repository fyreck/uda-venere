<?php
    require "./handler/conn.php";
    require "./handler/auth.php";

    if(!$loggato || !$mod){
        header("Location: ./login.php");
        exit();
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
                <button class="btn-nav btn-dash">
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
                    <button class="btn-nav btn-users focus" style="display: <?= $mod ? 'block' : 'none' ?>;">
                        <a href="./gestione">
                            <i class="fa-solid fa-gear fa-2xl"></i>
                        </a>
                    </button>
                </div>
            </div>
        </nav>

        <h2 class="titolo-eventi" style="text-align: center;">GESTIONE EVENTI</h2>
        <filedset class="gestione-eventi">
            <div class="add-evento" style="display: flex; margin-top: 60px; cursor: pointer;" id="idplus" onclick="openCard('idplus')">
                <div class="btn-add-evento" style="margin: auto; background-color: transparent; display: <?= $loggato ? 'block' : 'none' ?>">
                    <i class="fa-solid fa-circle-plus fa-2xl" style="background-color: transparent; font-size: 70px; color: #DFAB44;"></i>
                </div>                                       
            </div>

            <div class="update-evento" style="display: flex; margin-top: 60px; cursor: pointer;" id="idplus" onclick="openCard('idinfo')">
                <div class="btn-add-evento" style="margin: auto; background-color: transparent; display: <?= $loggato ? 'block' : 'none' ?>">
                    <i class="fa-solid fa-circle-info fa-2xl" style="background-color: transparent; font-size: 70px; color: #DFAB44;"></i>
                </div>                                       
            </div>

            <div class="update-evento" style="display: flex; margin-top: 60px; cursor: pointer;" id="idplus" onclick="openCard('idminus')">
                <div class="btn-add-evento" style="margin: auto; background-color: transparent; display: <?= $loggato ? 'block' : 'none' ?>">
                    <i class="fa-solid fa-circle-xmark fa-2xl" style="background-color: transparent; font-size: 70px; color: #DFAB44;"></i>
                </div>                                       
            </div>

            <div class="card-modal-overlay" id="modal-idplus">
                <div id="card-modal-content">
                    <button class="card-modal-close" onclick="closeCard('idplus')">×</button>
                    <div class="card-enlarged">
                        <h3>INSERISCI EVENTO</h3>
                        
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
                                    <input type="date" name="data" required>
                                    <label for="data evento">Inserire data evento</label>
                                </div>
                                <div class="input-field">
                                    <input type="time" name="ora" required>
                                    <label for="ora evento">Inserire ora evento</label>
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

            <div class="card-modal-overlay" id="modal-idinfo">
                <div id="card-modal-content">
                    <button class="card-modal-close" onclick="closeCard('idinfo')">×</button>
                    <div class="card-enlarged">
                        <h3>MODIFICA EVENTO</h3>
                        
                        <div class="wrapper">
                            <form id="form-uno" method="POST">
                                <div class="input-field">
                                    <label for="evento">Scegliere il titolo dell'evento da modificare</label><br><br>
                                    <select name="evento">
                                    <?php
                                        $query = "SELECT Titolo FROM EVENTO";
                                        $result = $conn->query($query);
                                        if ($result->num_rows > 0) :
                                            while ($row = $result->fetch_assoc()):?>
                                                <option value="<?= $row['Titolo'] ?>"><?= $row['Titolo'] ?></option>
                                            <?php endwhile;
                                        endif;
                                    ?>
                                    </select>
                                </div>
                                <button type="submit" onclick="openCard('idmore')">ELIMINA</button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-modal-overlay" id="modal-idmore">
                <div id="card-modal-content">
                    <button class="card-modal-close" onclick="closeCard('idmore')">×</button>
                    <div class="card-enlarged">
                        <h3>INSERISCI EVENTO</h3>
                        
                        <div class="wrapper">
                        <form action="./handler/modifica_handler.php" method="POST" enctype="multipart/form-data" id="form-due">
                                <div class="input-field">
                                    <input type="text" name="titolo" required>
                                    <label for="titolo">Inserire titolo evento</label>
                                </div>
                                <div class="input-field">
                                    <input type="text" name="luogo" placeholder="Edificio, Città" required>
                                    <label for="luogo evento">Inserire luogo evento</label>
                                </div>
                                <div class="input-field">
                                    <input type="date" name="data" required>
                                    <label for="data evento">Inserire data evento</label>
                                </div>
                                <div class="input-field">
                                    <input type="time" name="ora" required>
                                    <label for="ora evento">Inserire ora evento</label>
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
                                <button type="submit">MODIFICA</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-modal-overlay" id="modal-idminus">
                <div id="card-modal-content">
                    <button class="card-modal-close" onclick="closeCard('idminus')">×</button>
                    <div class="card-enlarged">
                        <h3>ELIMINA EVENTO</h3>
                        
                        <div class="wrapper">
                            <form action="./handler/eliminazione_handler.php" method="POST" enctype="multipart/form-data">
                                <div class="input-field">
                                    <label for="evento">Scegliere il titolo dell'evento da eliminare</label><br><br>
                                    <select name="evento">
                                        <?php
                                            $query = "SELECT Titolo FROM EVENTO";
                                            $result = $conn->query($query);
                                            if ($result->num_rows > 0) :
                                                while ($row = $result->fetch_assoc()):?>
                                                    <option value="<?= $row['Titolo'] ?>"><?= $row['Titolo'] ?></option>
                                                <?php endwhile;
                                            endif;
                                        ?>
                                    </select>
                                </div>
                                <button type="submit">ELIMINA</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </filedset>

    </div>

    <script src="./src/cards.js"></script>
    <script>
        <script>
        document.getElementById("form-uno").addEventListener("submit", function(e) {
            e.preventDefault();

            const formUno = document.forms["form-uno"];
            const formDue = document.forms["form-due"];

            document.getElementById("form-uno").style.display = "none";
            document.getElementById("form-due").style.display = "block";
        });
    </script>
    </script>
</body>
