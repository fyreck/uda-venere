<?php
    require "./handler/conn.php";
    require "./handler/auth.php";

    if(!$loggato || !$owner){
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
            	<a href="./dashboard.php">
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
                  <button class="btn-nav btn-personal">
                    <i class="fa-solid fa-user fa-2xl"></i>
                  </button>
                </a>
                <a href="./gestione.php" class="btn-nav">
                  <button class="btn-nav btn-users focus" style="display: <?= $owner ? 'block' : 'none' ?>;">    
                    <i class="fa-solid fa-gear fa-2xl"></i>
                  </button> 
                </a>
            </div>
        </nav>

        <div class="wrapper wrapper-query" style="width: 80%;">
            <h2 class="titolo-eventi">GESTIONE QUERY</h2>
            <h3>PAGINA DI VISUALIZZAZIONE QUERY A SCOPO DIDATTICO*</h3><br><br>
			
            <h3>I titoli e le date delle esposizioni tematiche che si sono tenute nel periodo 1 gennaio – 31
                dicembre di un determinato anno</h3><br><br>
            <form class="query query-uno" action="./handler/query-uno-handler.php" method="POST">
                <label for="anni">seleziona anno</label>
                <select name="anni">

                    <?php
                        $q = "SELECT DISTINCT YEAR(DataEvento) AS anno FROM EVENTO ORDER BY anno DESC";
                        $r = $conn->query($q);
                        if($r->num_rows>0){
                            foreach($r as $anno){
                                echo '<option value="' . $anno['anno'] . '">' . $anno['anno'] . '</option>';
                            }
                        }
                    ?>
                
                </select>

                <button type="submit">CERCA</button>
            </form><br><br><br>
            
            <h3>Il numero dei biglietti emessi per una determinata esposizione</h3><br><br>
            <form class="query query-due" action="handler/query-due-handler.php" method="POST">
                <label for="anni">seleziona anno</label>
                <select name="eventi">

                    <?php
                        $q = "SELECT Titolo FROM EVENTO";
                        $r = $conn->query($q);
                        if($r->num_rows>0){
                            foreach($r as $e){
                                echo '<option value="' . $e['Titolo'] . '">' . $e['Titolo'] . '</option>';
                            }
                        }
                    ?>
                
                </select>

                <button type="submit">CERCA</button>
            </form><br><br><br>
            
            <h3>Il ricavato della vendita dei biglietti di una determinata esposizione</h3><br><br>
            <form class="query query-tre" action="handler/query-tre-handler.php" method="POST">
                <label for="anni">seleziona anno</label>
                <select name="anni">

                    <?php
                        $q = "SELECT Titolo FROM EVENTO";
                        $r = $conn->query($q);
                        if($r->num_rows>0){
                            foreach($r as $e){
                                echo '<option value="' . $e['Titolo'] . '">' . $e['Titolo'] . '</option>';
                            }
                        }
                    ?>
                
                </select>

                <button type="submit">CERCA</button>
            </form>
        </div>
        



    </div>

    
</body>
