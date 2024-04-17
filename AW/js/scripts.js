// Referenciar el campo de búsqueda y el contenedor de resultados
const searchInput = document.getElementById('searchInput');
const resultsContainer = document.getElementById('results');

// Agregar un evento de escucha para detectar cambios en el campo de búsqueda
searchInput.addEventListener('input', function(event) {
    // Obtener el valor actual del campo de búsqueda
    const query = event.target.value;

    // Enviar una solicitud al servidor para buscar resultados correspondientes
    fetch(`/AW/search2.php?q=${query}`)
        .then(response => response.text())
        .then(data => {
            // Actualizar el contenido del contenedor de resultados con los resultados de la búsqueda
            resultsContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Error al realizar la búsqueda:', error);
        });
});

function mostrarPantallaEntrada(entrada) {
    generarCodigoQR(entrada);

    var pantallaEncima = document.getElementById("pantalla-entrada");
    pantallaEncima.style.display = "flex";
}
function cerrarPantallaEntrada() {
    var pantallaEncima = document.getElementById("pantalla-entrada");
    pantallaEncima.style.display = "none"; // Oculta la pantalla encima
}
function generarCodigoQR(texto) {
    // Llama a la función qrcode con los parámetros necesarios
    var qr = qrcode(0, 'L');
    qr.addData(texto);
    qr.make();
    // Muestra el código QR en el elemento con el ID "codigo-qr"
    document.getElementById("codigo-qr").innerHTML = qr.createImgTag(10, 10);
}

