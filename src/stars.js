//Get context and screen size;
var ctx = cnv.getContext("2d");
var W = window.innerWidth;
var H = window.innerHeight;

//Set Canvas and Background Color;
cnv.width = W;
cnv.height = H;
ctx.fillStyle = "#112";
ctx.fillRect(0, 0, W, H);

//Glow effect;
ctx.shadowBlur = 100;
ctx.shadowColor = "white";

let stars = []; // Array per memorizzare le stelle
let maxStars = 100; // Numero massimo di stelle

function createStar() {
	let x = W * Math.random();
	let y = H * Math.random();
	let r = 2.5 * Math.random();

	return { x: x, y: y, r: r };
}

function drawStar(star) {
	ctx.beginPath();
	ctx.fillStyle = "white";
	ctx.arc(star.x, star.y, star.r, 0, Math.PI * 2);
	ctx.fill();
}

function animate() {
	if (stars.length < maxStars) {
		let newStar = createStar();
		stars.push(newStar);
	} 

	ctx.clearRect(0, 0, W, H); // Clear canvas before redrawing
	ctx.fillStyle = "#112";
	ctx.fillRect(0, 0, W, H);

	stars.forEach(drawStar);

	setTimeout(animate, 1000);
}

animate();