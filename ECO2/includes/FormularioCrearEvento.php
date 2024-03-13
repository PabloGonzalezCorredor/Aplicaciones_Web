<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/Evento.php';

class FormularioCrearEvento extends Formulario
{
    public function __construct() {
        parent::__construct('formCrearEvento', ['urlRedireccion' => 'index.php', 'enctype' => 'multipart/form-data']);
    }
    
    protected function generaCamposFormulario(&$datos)
    {
        $nombreEvento = $datos['nombreEvento'] ?? '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['imagen', 'nombreEvento', 'fecha', 'horaIni', 'horaFin', 'localizacion', 'tarifas'], $this->errores, 'span', array('class' => 'error'));

        $html = <<<HTML
        $htmlErroresGlobales
        <fieldset>
            <div>
                <label for="imagen">Imagen:</label>
                <input id="imagen" type="file" accept="image/jpg, image/jpeg, image/png" name="imagen"/>
                {$erroresCampos['imagen']}
            </div>
            <div>
                <label for="nombreEvento">Nombre:</label>
                <input id="nombreEvento" type="text" name="nombreEvento"/>
                {$erroresCampos['nombreEvento']}
            </div>
            <div>
                <label for="fecha">Fecha:</label>
                <input id="fecha" type="date" name="fecha"/>
                {$erroresCampos['fecha']}
            </div>
            <div>
                <label for="horaIni">Hora de inicio:</label>
                <input id="horaIni" type="time" name="horaIni" />
                {$erroresCampos['horaIni']}
            </div>
            <div>
                <label for="horaFin">Hora final:</label>
                <input id="horaFin" type="time" name="horaFin" />
                {$erroresCampos['horaFin']}
            </div>
            <div>
                <label for="localizacion">Localización:</label>
                <input id="localizacion" type="text" name="localizacion" />
                {$erroresCampos['localizacion']}
            </div>

            <div id="tarifas">
                <label for="tarifa">Tarifa:</label>
                <div class="tarifa">
                    <label for="precio">Precio:</label>
                    <input id="precio" type="number" name="precio[]"/>
                    <label for="consumiciones">Consumiciones:</label>
                    <input id="consumiciones" type="number" name="consumiciones[]" />
                    <label for="informacion">Información:</label>
                    <input id="informacion" type="text" name="informacion[]" />
                </div>
            </div>
            <button id="agregarTarifa" type="button">+</button>
            {$erroresCampos['tarifas']}

            <div>
                <button type="submit" name="registrar">Crear Evento</button>
            </div>
        </fieldset>
        HTML;
        return $html;
    }
    

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $imagen = $_FILES['imagen'];

        if ( $imagen['error'] !== UPLOAD_ERR_OK) {
            $this->errores['imagen'] = 'Debe introducir una imagen para el evento.';
        } else {
            $rutaTemporal = $imagen['tmp_name'];

            $contenidoImagen = file_get_contents($rutaTemporal);
            $imagenBinaria = base64_encode($contenidoImagen);
        }

        $nombreEvento = trim($datos['nombreEvento'] ?? '');
        $nombreEvento = filter_var($nombreEvento, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreEvento || mb_strlen($nombreEvento) < 5) {
            $this->errores['nombreEvento'] = 'El nombre del evento tiene que tener una longitud de al menos 5 caracteres.';
        }

        $fecha = trim($datos['fecha'] ?? '');
        $fecha = filter_var($fecha, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $hoy = new DateTime('today');
        $fechaComprobada = DateTime::createFromFormat('Y-m-d', $fecha);
        if ( ! $fecha || $fechaComprobada < $hoy) {
            $this->errores['fecha'] = 'La fecha no puede haber pasado ya';
        }

        $horaIni = trim($datos['horaIni'] ?? '');
        $horaIni = filter_var($horaIni, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreEvento || mb_strlen($nombreEvento) < 5) {
            $this->errores['nombreEvento'] = 'El nombre del evento tiene que tener una longitud de al menos 5 caracteres.';
        }

        $horaFin = trim($datos['horaFin'] ?? '');
        $horaFin = filter_var($horaFin, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $nombreEvento || mb_strlen($nombreEvento) < 5) {
            $this->errores['nombreEvento'] = 'El nombre del evento tiene que tener una longitud de al menos 5 caracteres.';
        }

        $localizacion = trim($datos['localizacion'] ?? '');
        $localizacion = filter_var($localizacion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $localizacion || mb_strlen($localizacion) < 5) {
            $this->errores['localizacion'] = 'La localización debe tener una longitud de al menos 5 caracteres.';
        }

        // Obtener precios del arreglo $datos['precio']
        $precios = $datos['precio'] ?? [];
        // Filtrar y limpiar los precios
        $preciosFiltrados = [];
        foreach ($precios as $precio) {
            // Aplicar trim() a cada precio y verificar si no está vacío
            $precio = trim($precio);
            if ($precio !== '' && $precio !== null) {
                // Agregar el precio filtrado al nuevo arreglo
                $preciosFiltrados[] = $precio;
            }
        }
        // Obtener precios del arreglo $datos['precio']
        $consumicion = $datos['consumiciones'] ?? [];
        // Filtrar y limpiar los precios
        $consumicionesFiltradas = [];
        foreach ($consumicion as $con) {
            // Aplicar trim() a cada precio y verificar si no está vacío
            $con = trim($con);
            if ($con !== '' && $con !== null) {
                // Agregar el precio filtrado al nuevo arreglo
                $consumicionesFiltradas[] = $con;
            }
        }
        $informacion = $datos['informacion'] ?? [];

        if (count($preciosFiltrados) === 0 || count($consumicionesFiltradas) === 0) {
            $this->errores['tarifas'] = 'Debe proporcionar al menos una tarifa';
        } else {
            // Procesamos cada tarifa
            $tarifas = [];
            for ($i = 0; $i < count($preciosFiltrados); $i++) {
                $precio = trim($preciosFiltrados[$i]);
                $consumicion = trim($consumicionesFiltradas[$i]);
                $info = trim($informacion[$i]);
        
                // Si no hay errores, agregamos la tarifa al array
                if (!isset($this->errores['tarifas'])) {
                    $tarifas[] = [
                        'precio' => $precio,
                        'consumiciones' => $consumicion,
                        'informacion' => $info
                    ];
                }
            }
        }

        if (count($this->errores) === 0) {
            $evento = Evento::buscaEvento($nombreEvento, $fecha);
	
            if ($evento) {
                $this->errores[] = "El evento ya existe";
            } else {
                $evento = Evento::crea($imagenBinaria, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion, $tarifas);
            }
        }
    }
}

?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const agregarTarifaBtn = document.getElementById("agregarTarifa");
        const tarifasContainer = document.getElementById("tarifas");

        agregarTarifaBtn.addEventListener("click", function() {
            const nuevaTarifa = document.createElement("div");
            nuevaTarifa.classList.add("tarifa");
            nuevaTarifa.innerHTML = `
                <label for="precio">Precio:</label>
                <input id="precio" type="number" name="precio[]"/>
                <label for="consumiciones">Consumiciones:</label>
                <input id="consumiciones" type="number" name="consumiciones[]" />
                <label for="informacion">Información:</label>
                <input id="informacion" type="text" name="informacion[]" />
                <button class="eliminarTarifaBtn">Eliminar</button>
            `;
            tarifasContainer.appendChild(nuevaTarifa);

            // Añadir evento de click al botón de eliminar
            const eliminarTarifaBtn = nuevaTarifa.querySelector(".eliminarTarifaBtn");
            eliminarTarifaBtn.addEventListener("click", function() {
            nuevaTarifa.remove();
        });
        });
    });
</script>