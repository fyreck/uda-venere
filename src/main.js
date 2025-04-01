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

    document.getElementById("slideNumber").textContent = `Slide: ${imageIndex + 1}`;

    // Calcola la traslazione usando la funzione calcolaTraslazione
    const translateValue = calcolaTraslazione(imageIndex);
    document.getElementById("planet-frame").style.transform = `translateX(${translateValue}%)`;
});

function calcolaTraslazione(slideIndex) {
    let translateValue;

    if (slideIndex <= 0) {
        translateValue = -50;
    } else if (slideIndex >= imageCount -1 ) {
        translateValue = -90;
    } else {
        // Calcola una traslazione lineare tra -50 e -90
        translateValue = -50 - ((slideIndex / (imageCount -1)) * 40);
    }
    
    return translateValue;
}