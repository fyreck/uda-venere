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
    <title>Area Personale</title>
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
    </header>

    <nav style="display: <?= $loggato ? 'block' : 'none' ?> ">
        <div class="btn-container">
            <a href="./dashboard.php" class="btn-nav">
                	<button class="btn-nav btn-dash">
                        <i class="fa-solid fa-list-ul fa-2xl"></i>
                	</button>
              	</a>
            <a href="./eventi_personali.php" class="btn-nav">
              <button class="btn-nav btn-tickets">
                <i class="fa-solid fa-ticket fa-2xl"></i>
              </button>
            </a>
            <a href="./area_personale.php" class="btn-nav">
              <button class="btn-nav btn-personal focus">
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

    <br>

    <?php
        $query = "SELECT Nome, Cognome, Email, TipoUtente, Newsletter FROM UTENTE WHERE NomeUtente = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
    ?>

    <div class="wrapper-forms">
        <form action="./handler/userdata-handler/username_handler.php" method="POST" class="data-handler">
            <label for="username update">USERNAME</label>
            <input type="text" name="username" placeholder="<?= $user ?>" class="data-input">
            <button type="submit">MODIFICA</button>
        </form>

        <form action="./handler/userdata-handler/name_handler.php" method="POST" class="data-handler">
            <label for="username update">NOME</label>
            <input type="text" name="nome" placeholder="<?= $row['Nome'] ?>" class="data-input">
            <button type="submit">MODIFICA</button>
        </form>

        <form action="./handler/userdata-handler/surname_handler.php" method="POST" class="data-handler">
            <label for="username update">COGNOME</label>
            <input type="text" name="cognome" placeholder="<?= $row['Cognome'] ?>" class="data-input">
            <button type="submit">MODIFICA</button>
        </form>

        <form action="./handler/userdata-handler/mail_handler.php" method="POST" class="data-handler">
            <label for="username update">EMAIL</label>
            <input type="text" name="mail" placeholder="<?= $row['Email'] ?>" class="data-input">
            <button type="submit">MODIFICA</button>
        </form>

        <form action="./handler/mod-handler.php" class="data-handler" method="POST">
            <label for="tipo utente">TIPO</label>
            <input type="text" name="tipo" placeholder="<?= $row['TipoUtente'] ?>" readonly>

            <?php
                $q = "SELECT * FROM PARTECIPAZIONE AS P, ARTISTA AS A WHERE P.Artista = A.IDArtista AND A.Nome = ? AND A.Cognome = ?";
                $s = $conn->prepare($q);
                $nome = $row['Nome'];
                $cognome = $row['Cognome'];
                $s->bind_param("ss", $nome, $cognome);
                $s->execute();
                $resultCount=$s->get_result();
                    
                if($resultCount->num_rows >= 3 && !$mod){
                    echo "<button type='submit' class='btn btn-mod'>DIVENTA MODERATORE</button>";
                }
            ?>
        </form>

    </div>

    <section class="buttons">
        <section class="buttons btn-account">
            <form action="./handler/logout-handler.php" class="form-logout" method="POST">
                <button type="submit" class="btn-logout">LOGOUT ACCOUNT</button>
            </form>
            <form action="./handler/delete-handler.php" method="POST" class="form-delete">
                <button type="submit" class="btn-delete" onclick="return confirm('sicuro di voler eliminare questo account?')">ELIMINA ACCOUNT</button>
            </form>
        </section>
    </section>

</body>