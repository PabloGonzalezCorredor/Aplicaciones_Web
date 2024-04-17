<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/../SA/EventoSA.php';


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
        <fieldset class="form">
            <div style="display: flex; align-items: center">
                <label for="imagen">Imagen:</label>
                <input id="imagen" type="file" accept="image/jpg, image/jpeg, image/png" name="imagen"/>
            </div>
            {$erroresCampos['imagen']}
            <div style="display: flex; align-items: center">
                <label for="nombreEvento">Nombre:</label>
                <input id="nombreEvento" type="text" placeholder="Nombre del Evento"  name="nombreEvento"/>
            </div>
            {$erroresCampos['nombreEvento']}
            <div style="display: flex; align-items: center">
                <label for="fecha">Fecha:</label>
                <input id="fecha" type="date"  name="fecha"/>
            </div>
            {$erroresCampos['fecha']}
            <div style="display: flex; align-items: center">
                <label for="horaIni">Hora de inicio:</label>
                <input id="horaIni" type="time"  name="horaIni" />
            </div>
            {$erroresCampos['horaIni']}
            <div style="display: flex; align-items: center">
                <label for="horaFin">Hora final:</label>
                <input id="horaFin" type="time"  name="horaFin" />
            </div>
            {$erroresCampos['horaFin']}
            <div style="display: flex; align-items: center">
                <label for="localizacion">Localización:</label>
                <input id="localizacion" type="text" placeholder="Localización"  name="localizacion" />
            </div>
            {$erroresCampos['localizacion']}

            <div id="tarifas" class="tarifa-input-container1">
                <div style="display: flex;">
                    <label>Tarifa:</label>
                    <div class="tarifa-input-container1">
                        <div class="tarifa-input-container2">
                            <input id="consumiciones" type="number" placeholder="Consumiciones"  style="width: 200px" name="consumiciones[]" />
                            <h5>por<h5>
                            <input id="precio" type="number" placeholder="Precio"  style="width: 200px" name="precio[]"/>
                        </div>
                        <input id="informacion" type="text" placeholder="Información"  name="informacion[]" />
                    </div>
                </div>
            </div>
            <div style="display: flex; width: 100%; justify-content: center">
                <button id="agregarTarifa" type="button" class="add-tarifa"><h4>+<h4></button>
            </div>
            {$erroresCampos['tarifas']}
            <div style="display: flex; width: 100%; justify-content: center">
                <button type="submit" class="next-button" name="registrar"><h4>Crear Evento</h4></button>
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
        if ( ! $nombreEvento || mb_strlen($nombreEvento) < 3) {
            $this->errores['nombreEvento'] = 'El nombre del evento tiene que tener una longitud de al menos 3 caracteres.';
        }

        $fecha = trim($datos['fecha'] ?? '');
        $fecha = filter_var($fecha, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $hoy = new DateTime('today');
        $fechaComprobada = DateTime::createFromFormat('Y-m-d', $fecha);
        if ( !$fecha) {
            $this->errores['fecha'] = 'Debe introducir la fecha del evento';
        } else if ($fechaComprobada < $hoy) {
            $this->errores['fecha'] = 'La fecha no puede haber pasado ya';
        }

        $horaIni = trim($datos['horaIni'] ?? '');
        $horaIni = filter_var($horaIni, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $horaIni ) {
            $this->errores['horaIni'] = 'Debe introducir la hora a la que empieza el evento.';
        }

        $horaFin = trim($datos['horaFin'] ?? '');
        $horaFin = filter_var($horaFin, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $horaFin ) {
            $this->errores['horaFin'] = 'Debe introducir la hora a la que acaba el evento.';
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
            $existe = EventoSA::existeEvento($nombreEvento, $fecha);
	
            if ($existe) {
                $this->errores[] = "El evento ya existe";
            } else {
                $evento = EventoSA::creaEvento($_SESSION['id'], $imagenBinaria, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion, $tarifas);
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
            nuevaTarifa.style.display = "flex";

            nuevaTarifa.innerHTML = `
                <label>Tarifa:</label>
                <div class="tarifa-input-container1">
                    <div class="tarifa-input-container2">
                        <input id="consumiciones" type="number" placeholder="Consumiciones"  style="width: 200px" name="consumiciones[]" />
                        <h5>por<h5>
                        <input id="precio" type="number" placeholder="Precio"  style="width: 200px" name="precio[]"/>
                        <button class="delete-button"><h4>Eliminar</h4></button>
                    </div>
                    <input id="informacion" type="text" placeholder="Información"  name="informacion[]" />
                </div>
            `;
            tarifasContainer.appendChild(nuevaTarifa);

            // Añadir evento de click al botón de eliminar
            const eliminarTarifaBtn = nuevaTarifa.querySelector(".delete-button");
            eliminarTarifaBtn.addEventListener("click", function() {
                nuevaTarifa.remove();
            });
        });
    });
</script>
