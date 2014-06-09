// JavaScript Document
/* ARRAY DE IMAGENES */
ads = new Array(3);
ads[0] = "casual.png";
ads[1] = "socialmedia1.jpg";
ads[2] = "pub.1.jpg"


//variable para llevar la cuenta de las imagenes
var longuitudArray = ads.length;
var contador = 0

// Cojemos un numero aleatorio
contador = Math.floor((Math.random() * longuitudArray))

// Cambia la imagen cada segundo en este ejemplo
var tiempo = 6// En segundos
var timer = tiempo * 1000;

function publicidad() {
	contador++;
	contador = contador % longuitudArray
	document.publicidad.src = ads[contador];
	setTimeout("publicidad()", timer);
}

