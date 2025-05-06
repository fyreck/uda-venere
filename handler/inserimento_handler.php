<?php
    require "./conn.php";
    require "./auth.php";

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $titolo = htmlentities($_POST['titolo']);
        $luogo = htmlentities($_POST['luogo']);
        $data = htmlentities($_POST['data']);
        $ora = htmlentities(($_POST['ora']));
        $categoria = htmlentities($_POST['categoria']);

        // echo $categoria; //output di debug

        $queryCategoria = "SELECT IDCategoria FROM CATEGORIAINTERESSE WHERE Nome = ?";
        $stmtCategoria = $conn->prepare($queryCategoria);
        $stmtCategoria->bind_param("s", $categoria);
        $stmtCategoria->execute();
        $resultCat = $stmtCategoria->get_result();
        $rowCategoria = $resultCat->fetch_assoc();
        $IDCategoria = $rowCategoria['IDCategoria'];

        // echo $IDCategoria;  //output di debug

        $descrizione = htmlentities($_POST['descrizione']);
        
        $cartellaImmagini = "images/eventi/";
        
        $query = "SELECT * FROM EVENTO";
        $result = $conn->query($query);

        if($result==='FALSE') echo "errore query di selezione nome categorie";
        else{
            $nomeOriginale = $_FILES["immagine"]["name"];
            $estensione = pathinfo($nomeOriginale, PATHINFO_EXTENSION);
            $proxEvento = $result->num_rows + 1;
            $nomeImmagine = "evento" . $proxEvento . "." . $estensione;
            $percorsoImg = $cartellaImmagini . $nomeImmagine;

            if($mod){
                $stato = "ACCETTATO"; 
            }else{
                $stato = "IN ATTESA";
            }

            $queryCheck = "SELECT * FROM EVENTO WHERE Titolo = ? AND DataEvento = ? AND OraEvento = ?";
            $stmtCheck = $conn->prepare($queryCheck);
            $stmtCheck->bind_param("sss", $titolo, $data, $ora);
            $stmtCheck->execute();
            $resutlCheck = $stmtCheck->get_result();

            if($resutlCheck->num_rows == 0){

                if (move_uploaded_file($_FILES["immagine"]["tmp_name"], "../" . $percorsoImg)) {
                    

                    $queryInserimento = "INSERT INTO EVENTO (IDevento, Titolo, DataEvento, OraEvento, Luogo, Categoria, Descrizione, Immagine, Stato) VALUES (?,?,?,?,?,?,?,?,?)";
                    $stmtInserimento = $conn->prepare($queryInserimento);
                    $stmtInserimento->bind_param("issssisss", $proxEvento,  $titolo, $data, $ora, $luogo, $IDCategoria, $descrizione, $percorsoImg, $stato);

                    if($stmtInserimento->execute()===TRUE){

                        $queryUser = "SELECT Nome, Cognome FROM UTENTE WHERE NomeUtente = ?";
                        $stmtUser = $conn->prepare($queryUser);
                        $stmtUser->bind_param("s", $user);
                        $stmtUser->execute();
                        $resultUser = $stmtUser->get_result();
                        $rowUser = $resultUser->fetch_assoc();

                        $nomeArtista = $rowUser['Nome'];
                        $cognomeArtista = $rowUser['Cognome'];

                        $queryArtista = "SELECT IDArtista FROM ARTISTA WHERE Nome = ? AND Cognome = ?";
                        $stmtArtista = $conn->prepare($queryArtista);
                        $stmtArtista->bind_param("ss", $nomeArtista, $cognomeArtista);
                        $stmtArtista->execute();
                        $resultArtista = $stmtArtista->get_result();

                        $IDArtista = null;

                        if($resultArtista->num_rows == 0){
                            $queryInsertArtista = "INSERT INTO ARTISTA (Nome, Cognome) VALUES (?,?)";
                            $stmtInsertArtista = $conn->prepare($queryInsertArtista);
                            $stmtInsertArtista->bind_param("ss", $nomeArtista, $cognomeArtista);
                            if($stmtInsertArtista->execute()){
                                $IDArtista = $conn->insert_id;
                            }
                        }
                        else{
                            $rowArtista = $resultArtista->fetch_assoc();
                            $IDArtista = $rowArtista['IDArtista'];
                        }

                        $queryPartecipazione = "INSERT INTO PARTECIPAZIONE VALUES (?,?)";
                        $stmtPartecipazione = $conn->prepare($queryPartecipazione);
                        $stmtPartecipazione->bind_param("ii", $proxEvento, $IDArtista);
                        if($stmtPartecipazione->execute()){
                            header("Location: ../eventi_personali.php");
                            exit();
                            //TODO: header to sezione specifica di eventi in attesa di verifica
                        }
                        else{
                            $_SESSION['error'] = "Errore inserimento partecipazione artista-evento";
                            echo "Errore inserimento partecipazione artista-evento";
                        }

                    }
                    else{
                        $_SESSION['error'] = "Errore nell'inserimento dell'evento";
                        echo "Errore nell'inserimento dell'evento: "  . $conn->errno;
                    }

                } else {
                    $_SESSION['error'] = "Errore nel salvataggio dell'immagine.";
                    echo "Errore nel salvataggio dell'immagine.";
                }
            }
            else{
                $_SESSION['error'] = "Evento già presente";
                echo "Evento già presente";
            }
        }
    }
?>