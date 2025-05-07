<?php session_start(); ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UDA Venere</title>
    <link rel="icon" href="./images/favicon.png" type="image/x-icon">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./src/authstyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Style+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div  class="wrappersign">
        <h2>SIGN UP</h2>
        <label>Registrati per godere di tutti i servizi</label>

        <!-- FORM UNO -->
        <form id="form-uno">
            <div class="input-field">
                <input type="text" placeholder="..." name="username" required>
                <label for="username">Scegliere un proprio username</label>
            </div>
            <div class="input-field">
                <input type="text" name="nome" placeholder="..." required>
                <label for="nome_cognome">Inserire nome </label>
            </div>
            <div class="input-field">
                <input type="text" name="cognome" placeholder="..." required>
                <label for="nome_cognome">Inserire cognome </label>
            </div>
            <div class="input-field">    
                <input type="email" name="mail" placeholder="..." required>
                <label for="mail">Inserire la propria email</label>
            </div>
            <div class="input-field">
                <input type="password" name="pw" placeholder="..." required>
                <label for="password">Inserire la propria password</label>
            </div>
            <div class="input-field">
                <input type="checkbox" name="newsletter" value="SI">
                <label for="newsletter">Vuoi partecipare alla newsletter?</label>
            </div>

            <button type="submit" id="btn-next">CONTINUA</button>

            <div class="register">
                <p>Hai un account? <a href="./login.php" style="text-decoration: none; color: #D9D9D9">Accedi</a></p>
            </div>
        </form>
        <form id="form-due" action="handler/signup_handler.php" method="POST" style="display: none;">
            <?php
                require "./handler/conn.php";
                
                $query = "SELECT * FROM CATEGORIAINTERESSE";
                $result = $conn->query($query);
                
                if ($result){
                    while ($row = $result->fetch_assoc()): ?>
                        <input type="checkbox" name="categorie[]" value="<?= $row['Nome'] ?>">
                        <label><?= $row['Nome'] ?></label><br><br>
                    <?php endwhile;
                }
            ?>

            <input type="hidden" name="username">
            <input type="hidden" name="nome">
            <input type="hidden" name="cognome">
            <input type="hidden" name="mail">
            <input type="hidden" name="pw">
            <input type="hidden" name="newsletter" value="SI">

            <button type="submit">REGISTRATI</button>
        </form>
    </div>

    <script>
        document.getElementById("form-uno").addEventListener("submit", function(e) {
            e.preventDefault();

            const formUno = document.forms["form-uno"];
            const formDue = document.forms["form-due"];

            formDue.username.value = formUno.username.value;
            formDue.nome.value = formUno.nome.value;
            formDue.cognome.value = formUno.cognome.value;
            formDue.mail.value = formUno.mail.value;
            formDue.pw.value = formUno.pw.value;
            formDue.newsletter.value = formUno.newsletter.value;

            document.getElementById("form-uno").style.display = "none";
            document.getElementById("form-due").style.display = "block";
        });
    </script>

    <?php
        if (!empty($_SESSION['error'])) {
            echo "<p>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
    ?>

</body>