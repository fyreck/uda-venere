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

// Rimosso l'aggiornamento continuo dell'innerHTML
// setInterval(function() {
//     document.getElementById("content").innerHTML = scrollY;
// }, 1);

document.getElementById("planet-frame").style.transform = `translateX(-50%)`; // Imposta la posizione iniziale

window.addEventListener("scroll", () => {
    const scrollY = window.scrollY;
    const scrollHeight = document.body.scrollHeight - window.innerHeight;
    const scrollPercentage = scrollY / scrollHeight;
    let imageIndex = Math.floor(scrollPercentage * (imageCount - 1));
    imageIndex = Math.max(0, Math.min(imageIndex, imageCount - 1));
    document.getElementById("planet-frame").src = images[imageIndex];

    // document.getElementById("slideNumber").textContent = `Slide: ${imageIndex + 1}`;

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
    const numeroCasuale = Math.floor(Math.random() * 20) + 1; //indica qua ogni quanto vuoi che esca VENUSAUR

    const spanElement = document.getElementById("meme");

    if (numeroCasuale == 17) {
        spanElement.style.display = "inline";
    } else {
        spanElement.style.display = "none"; // Assicurati che sia nascosto quando non è il numero fortunato
    }
}

generaNumeroCasuale();