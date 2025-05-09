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
                        <a href="./gestione.php">
                            <i class="fa-solid fa-gear fa-2xl"></i>
                        </a>
                    </button>
                </div>
            </div>
        </nav>

        <?php
            if($_SERVER['REQUEST_METHOD']==='POST'){
                $evento = $_POST['evento'];

                $q = "SELECT * FROM EVENTO WHERE IDEvento = ?";
                $s = $conn->prepare($q);
                $s->bind_param("i", $evento);
                $s->execute();
                $result = $s->get_result();
                $row = $result->fetch_assoc();
            }
        ?>

        <h3>MODIFICA EVENTO</h3>
                        
            <div class="wrapper">
            <form action="./handler/modifica_handler.php" method="POST" enctype="multipart/form-data" id="form-due">
                    <div class="input-field">
                        <input type="text" name="titolo" value="<?= $row['Titolo'] ?>">
                        <label for="titolo">Inserire titolo evento</label>
                    </div>
                    <div class="input-field">
                        <input type="text" name="luogo" placeholder="Edificio, Città" value="<?= $row['Luogo'] ?>">
                        <label for="luogo evento">Inserire luogo evento</label>
                    </div>
                    <div class="input-field">
                        <?php $data_formattata = DateTime::createFromFormat('Y/m/d', $row['Data'])->format('Y-m-d'); ?>
                        <input type="date" name="data" value="<?= $data_formattata ?>">
                        <label for="data evento">Inserire data evento</label>
                    </div>
                    <div class="input-field">
                        <input type="time" name="ora" value="<?= $row['Ora'] ?>">
                        <label for="ora evento">Inserire ora evento</label>
                    </div>
                    <div class="input-field">
                        <input type="number"  name="posti" value="<?= $row['NumeroPosti'] ?>">
                        <label for="posti">Inserire numero posti disponibili</label>
                    </div>
                    <div class="input-field">
                        <input type="number" name="prezzo" value="<?= $row['Prezzo'] ?>">
                        <label for="prezzo evento">Inserire prezzo evento</label>
                    </div>
                    <div class="input-field">
                        <select name="categoria" >
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
                        <textarea name="descrizione"><?= $row['Descrizione'] ?></textarea>
                        <label for="descrizione"></label>
                    </div>
                    <div class="input-field">
                        <input type="file" name="immagine" accept="image/png, image/jpg, image/jpeg" value="<?= $row['Immagine'] ?>">
                        <img src="<?= $row['Immagine'] ?>">
                    </div>
                    <button type="submit">MODIFICA</button>
                </form>
            </div>

    </div>
</body>