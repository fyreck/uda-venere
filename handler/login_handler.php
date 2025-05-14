<?php
    session_start();

    require "conn.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $mail_user = htmlentities(trim($_POST["mail_user"]));
        $password = $_POST["pw"];

        if (filter_var($mail_user, FILTER_VALIDATE_EMAIL)) {
            $query = "SELECT * FROM UTENTE WHERE EMAIL = ?";
        } else {
            $query = "SELECT * FROM UTENTE WHERE NOMEUTENTE = ?";
        }

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $mail_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            $_SESSION['error'] = "Errore nell'elaborazione della query di ricerca per l'autenticazione";
            header("Location: ../login.php");
            exit();
        } else {
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['PASSWORD'])) {
                    $_SESSION['user'] = $mail_user;
                    $_SESSION['LAST_ACTIVITY']=time();
                    header("Location: ../dashboard.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Password errata";
                    header("Location: ../login.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "Utente non trovato";
                header("Location: ../login.php");
                exit();
            }
        }
    }
?>