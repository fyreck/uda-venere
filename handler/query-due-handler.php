<?php
    require "./conn.php";   
    require "./auth.php";

    if($conn->connect_errno){
        $_SESSION['error']='conn';
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

          <h3>Il numero dei biglietti emessi per una determinata esposizione</h3><br><br>

                  <?php

                      if($_SERVER['REQUEST_METHOD']=='POST'){
                          $evento = $_POST['eventi'];

                          $sql = "SELECT IDEvento FROM EVENTO as E, PRENOTAZIONE as P WHERE E.IDEvento = P.Evento";

                          $result = $conn->query($sql);

                          if ($result->num_rows > 0){ ?>
							<h3>Il numero di biglietti venduti per <span>"<?= $evento ?>"</span> è di: <span><?= $result->num_rows ?></span></h3>
				<?php	  } 
                          else{
                            echo "<p>Nessun biglietto venduto per quest'evento</p>";
                          }
                       }
				?>
		
        </div>

    </div> 
</body>
