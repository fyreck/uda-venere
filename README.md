# UDA Venere: Il Nostro Progetto Scolastico 🪐

Ciao! 👋 Benvenuti nel repository del progetto "UDA Venere", il sito web che abbiamo realizzato come team per la nostra Unità Didattica di Apprendimento dedicata all'affascinante pianeta Venere.

È un progetto nato tra i banchi di scuola, ma ci siamo impegnati per renderlo interessante e tecnicamente valido!

## 🚀 Guarda il Sito Online

Puoi esplorare il risultato del nostro lavoro visitando il sito pubblicato su Altervista:
[**https://udavenere.altervista.org/**](https://udavenere.altervista.org/)

## ✨ Le "Chicche" del Nostro Sito

Abbiamo cercato di non creare il solito sito statico, includendo alcune funzionalità degne di nota:

* **L'Index che Dà il Benvenuto:** La pagina principale (`index.html`) non è una semplice schermata di testo. Abbiamo integrato un modello 3D di Venere che ruota, dando subito un impatto visivo accattivante. Tutta roba fatta con `HTML`, `CSS`, e `JavaScript`!
* **Newsletter "Autonoma":** Sì, c'è una newsletter! Gli utenti possono iscriversi e noi possiamo inviare aggiornamenti. Il bello è che l'invio delle email è automatizzato grazie a uno script in `PHP` che viene eseguito periodicamente tramite un **cron job** sul server.
* **Occhio alla Visibilità:** Non volevamo che il nostro sito finisse nel dimenticatoio del web. Ci siamo rimboccati le maniche e abbiamo lavorato sull'ottimizzazione per i motori di ricerca, in particolare usando **Google Search Console** per l'indicizzazione e per tenere d'occhio come "vede" il nostro sito Google.
* **Sicurezza (Per Quanto Possibile):** Anche se è un progetto scolastico, la sicurezza non va trascurata. Abbiamo configurato il sito per utilizzare il protocollo **HTTPS**, garantendo una connessione criptata tra l'utente e il server.

## 🛠 Stack Tecnologico

Ecco un riepilogo delle tecnologie che abbiamo studiato e applicato per questo progetto:

* **Frontend:** `HTML5`, `CSS3`, `JavaScript` (con particolare attenzione a librerie/framework per la grafica 3D).
* **Backend:** `PHP` (gestione dati, invio email).
* **Automazione:** `Cron Job` (task scheduling sul server).
* **Hosting:** Altervista.
* **SEO Tools:** Google Search Console.
* **Strumenti di Sviluppo:** Git, GitHub.

## 🏗 Come Abbiamo Lavorato: Il Workflow con GitHub

Questo progetto è stato un banco di prova fantastico per imparare a lavorare in team usando **GitHub**. La nostra repository ([`https://github.com/fyreck/uda-venere`](https://github.com/fyreck/uda-venere)) è stata il cuore pulsante della nostra collaborazione.

Abbiamo sfruttato appieno il **controllo versione**: ogni modifica significativa è un **commit**, con un messaggio chiaro che spiega cosa è stato fatto. Per lavorare su funzionalità separate senza intralciarci, abbiamo utilizzato i **branch**. Ognuno poteva sviluppare la sua parte (es. la newsletter, la grafica 3D, una pagina specifica) sul proprio branch, per poi integrare il tutto nel branch principale (`main` o `master`) con un **merge** (spesso preceduto da una **Pull Request** - anche se per un piccolo team potremmo averle usate in modo snello). Questo approccio ci ha permesso di:

* Dividere chiaramente i compiti.
* Lavorare in parallelo.
* Avere uno storico completo di tutte le modifiche apportate.
* Risolvere eventuali conflitti tra versioni del codice.

Insomma, GitHub non è stato solo un posto dove caricare i file, ma uno strumento attivo per organizzare il nostro sviluppo!

## 👥 Il Nostro Team

Il successo di questo progetto è frutto del lavoro di squadra. Ecco come ci siamo suddivisi i ruoli:

* **Gestore del progetto:** Magro Tiziana
* **Moderatore:** De Bellis Marcello
* **Osservatore:** Brocca Marco
* **Addetto alle Pubbliche Relazioni:** Maldonato Alessandro

## 📸 Un'Anteprima

Ecco come si presenta la home page con il nostro Venere che ruota:

![Screenshot della pagina index con il pianeta Venere](/frame/1.png)

---
# 🖌️ Brand Identity
Abbiamo iniziato fin da subito a cercare quello che potesse essere il nome della web community fino a quando non abbiamo pensato a **VenUS**. Questo nome racchiudeva i due elementi chiave, *Venere*, ad indicare il pianeta fondatore del gruppo, e *US*, ad indicare la forza del rapporto della community. 
![Logo e palette cromatica](/images/Logo_Palette.png)
La scelta della palette cromatica è stato un altro passo fondamentale prima di proseguire nella realizzazione del sito. I colori richiamano il pianeta e i toni caldi sono fondamentali per trasmettere calore e serenità che sono i due punti cardine di una comunità.

---
# ⚙️ Alcune funzionalità
## 👤 Tipi di Utente
La community dispone di 4 tipologie di utenti:
![Gestione Utenti di vario tipo](/images/Users.png)
* l'**utente anonimo** che può visualizzare la vetrina del sito senza interagirci
* l'**utente basico** che può prenotarsi a degli eventi e richiedere di aggiungerne
* l'**utente mod** che può essere una grazia concessa o meritata dopo essere diventato un utente attivo nella community
* l'**utente assoluto** ovvero i fondatori di *VenUS*
## 🔄️ Il Pianeta Ruotante
```javascript
  document.addEventListener("DOMContentLoaded", function() {
      window.scrollTo({
          top: 0,
          left: 0,
          behavior: 'smooth'
      });
  });
  
  const imageCount = 80;
  let images = [];
  for (let i = 1; i <= imageCount; i++) {
      images.push(`frame/${i}.png`);
  }
  
  document.getElementById("planet-frame").style.transform = `translateX(-50%)`; // Imposta la posizione iniziale
  
  window.addEventListener("scroll", () => {
      const scrollY = window.scrollY;
      const scrollHeight = document.body.scrollHeight - window.innerHeight;
      const scrollPercentage = scrollY / scrollHeight;
      let imageIndex = Math.floor(scrollPercentage * (imageCount - 1));
      imageIndex = Math.max(0, Math.min(imageIndex, imageCount - 1));
      document.getElementById("planet-frame").src = images[imageIndex];
  
      // Calcola la traslazione usando la funzione calcolaTraslazione
      const translateValue = calcolaTraslazione(imageIndex);
      document.getElementById("planet-frame").style.transform = `translateX(${translateValue}%)`;
  
      // Gestione della visibilità dei div basata sullo scroll
      let primoDiv = document.getElementById("primo");
      if (scrollY >= 800 && scrollY < 1600) {
          primoDiv.classList.add("visible");
      } else {
          primoDiv.classList.remove("visible");
      }
  
      let secondoDiv = document.getElementById("secondo");
      if (scrollY >= 1800 && scrollY < 2600) {
          secondoDiv.classList.add("visible");
      } else {
          secondoDiv.classList.remove("visible");
      }
  
      let terzoDiv = document.getElementById("terzo");
      if (scrollY >= 2800) {
          terzoDiv.classList.add("visible");
      } else {
          terzoDiv.classList.remove("visible");
      }
  });
  
  function calcolaTraslazione(slideIndex) {
      let translateValue;
  
      if (slideIndex <= 0) {
          translateValue = -50;
      } else if (slideIndex >= imageCount - 1) {
          translateValue = -90;
      } else {
          // Calcola una traslazione lineare tra -50 e -90
          translateValue = -50 - ((slideIndex / (imageCount - 1)) * 40);
      }
  
      return translateValue;
  }
  
  function generaNumeroCasuale() {
      // Genera un numero casuale tra 1 e 20 (inclusi).
      const numeroCasuale = Math.floor(Math.random() * 20) + 1; //indica qua ogni quanto vuoi che esca VenUSaur
  
      const spanElement = document.getElementById("meme");
  
      if (numeroCasuale == 17) {
          spanElement.style.display = "inline";
      } else {
          spanElement.style.display = "none"; // Assicurati che sia nascosto quando non è il numero fortunato
      }
  }
  
  generaNumeroCasuale();
```
---
## 📧 La Newsletter
```php
require "./conn.php";
require "./auth.php"; 

$q = "SELECT u.Nome, u.Cognome, u.Email, e.Titolo, e.Descrizione, e.DataEvento, e.OraEvento, e.Luogo
      FROM UTENTE u
      JOIN PREFERENZA p ON u.NomeUtente = p.Utente
      JOIN CATEGORIAINTERESSE c ON p.Categoria = c.IDCategoria
      JOIN EVENTO e ON e.Categoria = c.IDCategoria
      WHERE
        u.Newsletter = 'SI'
        AND YEARWEEK(e.DataEvento, 1) = YEARWEEK(CURDATE(), 1)";

if ($result->num_rows > 0) {
// Definisci il soggetto dell'email
$subject = "Scopri nuovi i eventi che pensiamo possano entusiasmarti!";

// Inizia la struttura HTML dell'email (usa HEREDOC per maggiore leggibilità)
$html_template = <<<EOT
```
Vengono selezionati i dati necessari per la newsletter.
La newsletter deve essere inviata ogni inizio settimana, indicando agli utenti iscritti gli enventi che si svolgeranno nell'arco della settimana relativi alla categoria d'interesse da loro espressa.
Per ottenere gli eventi della settimana è stata usata la funzione di ```MYSQL YEARWEEK()```.
```YEARWEEK(e.DataEvento, 1)``` restituisce l’anno e il numero della settimana per la data dell’evento, considerando la settimana che inizia di lunedì (1)
```YEARWEEK(CURDATE(), 1)``` restituisce anno e numero della settimana per la data di oggi.

---

Di seguito si ha la struttura che andrà ad assumere la newsletter che viene inserita nella variabile ```$html_template```.
```html
<html>
  <head>
    <title>$subject</title>
    <style>
        :root {
            --primary-color: #CF6E0D; /* Arancione scuro */
            --secondary-color: #DFAB44; /* Giallo-arancio */
            --accent-color: #D81E5B; /* Rosa acceso */
            --primary-light: #D9D9D9; /* Grigio chiaro */
            --primary-dark: #181821; /* Quasi nero */
        }

      body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        color: var(--primary-dark); /* Testo scuro */
      }
      .container {
        width: 90%;
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid var(--primary-light); /* Bordo grigio chiaro */
        border-radius: 5px;
        background-color: var(--primary-light); /* Sfondo grigio chiaro */
      }
      .header {
        background-color: var(--primary-color); /* Intestazione arancione scuro */
        color: white;
        padding: 10px;
        text-align: center;
        border-radius: 5px 5px 0 0;
      }
      .content {
        padding: 20px;
      }
      .footer {
        font-size: 0.9em;
        text-align: center;
        color: #777; /* Mantenuto grigio per il footer */
        margin-top: 20px;
      }
      .button {
        display: inline-block;
        background-color: var(--accent-color); /* Bottone rosa acceso */
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 15px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <h1>Ciao [NOME] [COGNOME]!</h1>       </div>
      <div class="content">
        <p>Grandi notizie! Abbiamo nuovi eventi pronti per te.</p>
        
        <p>
          Lasciati ispirare da questi titoli e divertiti!
        </p>
        <ul>
        	[LISTA_EVENTI]
        </ul>
        <p><a href="../dashboard.php" class="button">Prenota Ora!</a></p>
        <hr />
      </div>
      <div class="footer">
        <p>VenUS&copy; [ANNO]</p>
      </div>
    </div>
  </body>
</html>
```
```php
EOT;
        // Fine HEREDOC

        // Intestazioni per l'email HTML
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: udavenere@altervista.org" . "\r\n"; 

        // Itera su ogni riga del risultato della query
        while ($row = $result->fetch_assoc()) {
            $to = $row['Email'];
            $nome = $row['Nome'];
            $cognome = $row['Cognome'];

            // Sostituisci i placeholder nell'HTML con i dati dell'utente
            $message = str_replace('[NOME]', $nome, $html_template);
            $message = str_replace('[COGNOME]', $cognome, $message);

            // Invio dell'email
            if (mail($to, $subject, $message, $headers)) {
                echo "Email inviata con successo a $to<br>";
            } else {
                echo "Errore nell'invio dell'email a $to<br>";
                // Puoi aggiungere un log degli errori qui
                // error_log("Errore invio email a $to: " . error_get_last()['message']);
            }
        }
    } else {
        echo "Nessun utente iscritto alla newsletter trovato.<br>";
    }

    // Chiudi la connessione al database
    $conn->close();
```
È stata usata la funzione ```mail``` di ```PHP``` per inviare la mail e la newsletter è stata automatizzata grazie ai ```cron job``` offerti da ```Altervista```.
### Heredoc
Tutto il corpo dell'email viene memorizzato in una variabile ```$html_template``` tramite l'operatore ```<<<EOT```. 
L'Heredoc permette di inserire tutto il codice ```HTML``` senza dover usare le virgolette per ogni riga, mantenendo la formattazione leggibile. In questo modo basta passare la variabile ```$html_template``` all'interno della funzione ```mail() ``` di ```php``` per inviare il template come corpo del messaggio.

---
## 📼 Scaricare le Immagini dalla Form
In fase di inserimento di un evento viene richiesta una immagine che verrà poi mostrata per enfatizzare il programma dell'evento.
Invece di permettere l'upload dell'immagine e perderne il riferimento, si è optato per scaricare l'immagine in una cartella apposita e inserire in database il percorso all'immagine. 
```php
$cartellaImmagini = "images/eventi/";
$nomeOriginale = $_FILES["immagine"]["name"];
$estensione = pathinfo($nomeOriginale, PATHINFO_EXTENSION);
$proxEvento = $result->num_rows + 1;
$nomeImmagine = "evento" . $proxEvento . "." . $estensione;
$percorsoImg = $cartellaImmagini . $nomeImmagine;

move_uploaded_file($_FILES["immagine"]["tmp_name"], "../" . $percorsoImg);
```
In questo modo è possibile spostare l'immagine, ora rinominata in modo che si relazioni con l'evento a cui appartiene, all'interno di una cartella dedicata e non più sezione in cui è stata caricata, in modo da avere sempre sotto mano le foto caricate.

---
## 💤 Inattività
```php
$timeout=180;
        
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout) {
    unset($_SESSION['user']);
    unset($_SESSION['LAST_ACTIVITY']);
    header("Location: ../login.php");
    exit;
} else {
    $_SESSION['LAST_ACTIVITY'] = time(); 
}
```
Viene verificata in ogni pagina l'ultima attività dell'utente e se questa risale a più di 3 minuti prima viene richiesto il login. ```time() - $_SESSION['LAST_ACTIVITY']) > $timeout``` fa la differenza tra il momento in cui sta venendo eseguito il controllo e l'ora dell'ultima attività.

---
## ☯️ Login con Username e/o Email
Per comodità è stata sviluppata questa funzionalità, permettendo agli utenti di scegliere se fare l'accesso con il loro username o con la loro email. Seppur solo ```NomeUtente``` è chiave, l'attibuto ```Email``` è stato impostato come ```UNIQUE``` per permettere questa funzionalità.
```php
$mail_user = htmlentities(trim($_POST["mail_user"]));

if (filter_var($mail_user, FILTER_VALIDATE_EMAIL)) {
    $query = "SELECT * FROM UTENTE WHERE EMAIL = ?";
} else {
    $query = "SELECT * FROM UTENTE WHERE NOMEUTENTE = ?";
}
```
La funzione ```filter_var()``` con attributo ```FILTER_VALIDATE_EMAIL``` di ```PHP``` permette di capire il se il parametro rispetta le caratteristiche di una email o meno. In base al risultato della funzione viene creata una query diversa che permette l'autenticazione corretta dell'utente.

---
## ♊ Due Form in Una
Nella stessa pagina di Sign-up sono presenti due form differenti visibili una alla volta per condurre l'utente passo dopo passo alla sua registrazione.
```html
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
        <input type="checkbox" name="newsletter">
        <label for="newsletter">Vuoi partecipare alla newsletter?</label>
    </div>
    <button type="submit" id="btn-next">CONTINUA</button>

    <div class="register">
        <p>Hai un account? <a href="./login.php" style="text-decoration: none; color: #D9D9D9">Accedi</a></p>
    </div>
</form>

<!-- FORM DUE -->
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
<input type="hidden" name="newsletter">			

    <button type="submit">REGISTRATI</button>
</form>
```
La prima form è statica e recupera i dati anagrafici dell'utente. La seconda è popolata dinamicamente con le categoria presenti in database. 
Per non perdere i dati caricati nella prima form, questi vengono manipolati con ```JavaScript``` e vengono impostati come ```value``` negli ```input``` con ```type='hidden'``` della seconda form.
```javascript
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
```
