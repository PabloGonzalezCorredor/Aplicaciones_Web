<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/../TO/Evento.php';
require_once __DIR__.'/../SA/EventoSA.php';

class FormularioModificarEvento extends Formulario {
    private $evento;

    public function __construct($evento = null) {
        parent::__construct('formModificarEvento', ['urlRedireccion' => 'index.php', 'enctype' => 'multipart/form-data']);
        $this->evento = $evento;
    }

    protected function generaCamposFormulario(&$datos) {
        // Obtener los datos del evento, si está disponible
        $nombreEvento = $this->evento ? $this->evento->getNombreEvento() : '';
        $fecha = $this->evento ? $this->evento->getFecha() : '';
        $horaIni = $this->evento ? $this->evento->getHoraIni() : '';
        $horaFin = $this->evento ? $this->evento->getHoraFin() : '';
        $localizacion = $this->evento ? $this->evento->getLocalizacion() : '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['imagen', 'nombreEvento', 'fecha', 'horaIni', 'horaFin', 'localizacion'], $this->errores, 'span', array('class' => 'error'));

        // Generar HTML para cada campo del formulario, rellenando los valores si están disponibles
        $html = <<<HTML
        $htmlErroresGlobales
        <div class="form">
            <div style="display: flex; align-items: center">
                <label for="imagen">Imagen:</label>
                <input id="imagen" type="file" name="imagen"/>
            </div>
            {$erroresCampos['imagen']}
            <div style="display: flex; align-items: center">
                <label for="nombreEvento">Nombre:</label>
                <input id="nombreEvento" type="text" placeholder="Nombre del Evento"  name="nombreEvento" value="$nombreEvento"/>
            </div>
            {$erroresCampos['nombreEvento']}
            <div style="display: flex; align-items: center">
                <label for="fecha">Fecha:</label>
                <input id="fecha" type="date"  name="fecha" value="$fecha"/>
            </div>
            {$erroresCampos['fecha']}
            <div style="display: flex; align-items: center">
                <label for="horaIni">Hora de inicio:</label>
                <input id="horaIni" type="time"  name="horaIni" value="$horaIni"/>
            </div>
            {$erroresCampos['horaIni']}
            <div style="display: flex; align-items: center">
                <label for="horaFin">Hora final:</label>
                <input id="horaFin" type="time"  name="horaFin" value="$horaFin"/>
            </div>
            {$erroresCampos['horaFin']}
            <div style="display: flex; align-items: center">
                <label for="localizacion">Localización:</label>
                <input id="localizacion" type="text" placeholder="Localización"  name="localizacion" value="$localizacion"/>
            </div>
            {$erroresCampos['localizacion']}
            <div style="display: flex; width: 100%; justify-content: center">
                <button type="submit" class="button" name="modificar"><h4>Modificar Evento</h4></button>
            </div>  
        </div>
        HTML;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $imagen = $_FILES['imagen'];

        if ($imagen['error'] !== UPLOAD_ERR_OK) {
            $imagenBinaria = $this->evento->getImagen();
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
        if ( ! $horaIni ) {
            $this->errores['horaIni'] = 'Debe introducir la hora a la que empieza el evento.';
        }

        $horaFin = trim($datos['horaFin'] ?? '');
        $horaFin = filter_var($horaFin, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $horaFin) {
            $this->errores['horaFin'] = 'Debe introducir la hora a la que acaba el evento.';
        }

        $localizacion = trim($datos['localizacion'] ?? '');
        $localizacion = filter_var($localizacion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $localizacion || mb_strlen($localizacion) < 5) {
            $this->errores['localizacion'] = 'La localización debe tener una longitud de al menos 5 caracteres.';
        }

        if (count($this->errores) === 0) {
            EventoSA::actualizarEvento($this->evento, $imagenBinaria, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion);
        }
    }

}
?>
