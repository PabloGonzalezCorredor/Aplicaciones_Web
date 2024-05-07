

// Referenciar el campo de búsqueda y el contenedor de resultados
const searchInput = document.getElementById('searchInput');
const resultsContainer = document.getElementById('results');

// Agregar un evento de escucha para detectar cambios en el campo de búsqueda
searchInput.addEventListener('input', function(event) {
    // Obtener el valor actual del campo de búsqueda
    const query = event.target.value;

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

function mostrarDatos(tipo) {
    var divPrivacidad = document.getElementById("privacidad");
    var divEdad = document.getElementById("edad");
    var divGenero = document.getElementById("genero");

    if (tipo == "usuario") {
        divPrivacidad.style.display = "block";
        divEdad.style.display = "block";
        divGenero.style.display = "block";
    } else {
        divPrivacidad.style.display = "none";
        divEdad.style.display = "none";
        divGenero.style.display = "none";
    }
}

function escanearQR() {
    const video = document.createElement('video');
    const container = document.getElementById('camara');
    container.appendChild(video);

    const boton = document.getElementById('scan-button');
    var texto = boton.querySelector('h4');
    var select = document.getElementById("event-select");
    var id = select.options[select.selectedIndex].value;

    const startScan = async () => {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = stream;
            await video.play();

            texto.textContent = "Parar";
            boton.onclick = function() {
                stream.getTracks().forEach(track => track.stop());
                container.removeChild(video);
                texto.textContent = "Escanear";
                boton.onclick = escanearQR;
            };


            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const scanQRCode = () => {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                if (code) {
                    stream.getTracks().forEach(track => track.stop());
                    container.removeChild(video);

                    texto.textContent = "Escanear";
                    boton.onclick = escanearQR;

                    window.location = "scan.php?codigo=" + code.data + "&id=" + id;
                } else {
                    requestAnimationFrame(scanQRCode);
                }
            };

            scanQRCode();
        } catch (error) {
            console.error('Error al acceder a la cámara:', error);
        }
    };

    startScan();
}




