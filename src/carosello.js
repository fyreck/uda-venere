document.addEventListener("DOMContentLoaded", function () {
  const thumbnails = document.querySelectorAll(".carousel__thumbnails label");
  const slides = document.querySelectorAll(
    ".carousel__slides .carousel__slide"
  );
  const radioButtons = document.querySelectorAll(
    '.carousel > input[name="slides"]'
  );

  thumbnails.forEach((thumbnail, index) => {
    thumbnail.addEventListener("click", function () {
      // Seleziona il radio button corrispondente all'indice della miniatura
      radioButtons[index].checked = true;

      // Potresti anche aggiungere una classe per evidenziare la miniatura attiva (opzionale)
      thumbnails.forEach((t) => t.classList.remove("active"));
      this.classList.add("active");
    });
  });
});
