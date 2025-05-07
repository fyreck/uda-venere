<?php
    $host = "localhost";
    $user = "root";
    $pw = "";
    $db = "VENERE";

    /*
    $host = "localhost";
    $user = "udavenere";
    $pw = "";
    $db = "my_udavenere";
    */

    $conn = new mysqli($host, $user, $pw, $db);

    if($conn->connect_errno) echo "<h1>Error 101</h1><br><h3>Errore di connessione con il server</h3>";
?>