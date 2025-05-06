<?php
    session_start();

    if(isset($_SESSION['success'])){
        echo "<p>" . $_SESSION['success'] . " Accedi!";
        unset($_SESSION['success']);
    }
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./src/authstyle.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Style+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <form action="./handler/login_handler.php" method="POST">
            <h2>Login</h2>
            <div class="input-field">
                <input type="text" name="mail_user" required>
                <label>Inserisci email o username</label>
            </div>
            <div class="input-field">
                <input type="password" name="pw" required>
                <label>Inserisci password</label>
            </div>
            <button type="submit">Log In</button>
            <div class="register">
                <p>Non hai un account? <a href="./signup.php">Registrati</a></p>
            </div>
        </form>
    </div>

    <?php
        if(isset($_SESSION['error'])){
            ?>
            <p><?= $_SESSION['error'] ?></p>
            <?php
            unset($_SESSION['error']);
        }
    ?>
        
    <!-- <script src="./src/main.js"></script> -->
    <!-- <script src="./src/stars.js"></script> -->
</body>
</html>