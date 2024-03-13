<?php
require_once __DIR__.'/Formulario.php';
require_once __DIR__.'/Evento.php';

class FormularioModificarEvento extends Formulario {
    private $evento;

    public function __construct($evento = null) {
        parent::__construct('formModificarEvento', ['urlRedireccion' => 'index.php', 'enctype' => 'multipart/form-data']);
        $this->evento = $evento;
    }

    protected function generaCamposFormulario(&$datos) {
        // Obtener los datos del evento, si está disponible
        $nombreEvento = $this->evento ? htmlspecialchars($this->evento->getNombreEvento()) : '';
        $fecha = $this->evento ? $this->evento->getFecha() : '';
        $horaIni = $this->evento ? $this->evento->getHoraIni() : '';
        $horaFin = $this->evento ? $this->evento->getHoraFin() : '';
        $localizacion = $this->evento ? htmlspecialchars($this->evento->getLocalizacion()) : '';

        // Se generan los mensajes de error si existen.
        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['imagen', 'nombreEvento', 'fecha', 'horaIni', 'horaFin', 'localizacion'], $this->errores, 'span', array('class' => 'error'));

        // Generar HTML para cada campo del formulario, rellenando los valores si están disponibles
        $html = <<<HTML
        $htmlErroresGlobales
        <fieldset>
            <div>
                <label for="imagen">Imagen:</label>
                <input id="imagen" type="file" name="imagen"/>
                {$erroresCampos['imagen']}
            </div>
            <div>
                <label for="nombreEvento">Nombre:</label>
                <input id="nombreEvento" type="text" name="nombreEvento" value="$nombreEvento"/>
                {$erroresCampos['nombreEvento']}
            </div>
            <div>
                <label for="fecha">Fecha:</label>
                <input id="fecha" type="date" name="fecha" value="$fecha"/>
                {$erroresCampos['fecha']}
            </div>
            <div>
                <label for="horaIni">Hora de inicio:</label>
                <input id="horaIni" type="time" name="horaIni" value="$horaIni"/>
                {$erroresCampos['horaIni']}
            </div>
            <div>
                <label for="horaFin">Hora final:</label>
                <input id="horaFin" type="time" name="horaFin" value="$horaFin"/>
                {$erroresCampos['horaFin']}
            </div>
            <div>
                <label for="localizacion">Localización:</label>
                <input id="localizacion" type="text" name="localizacion" value="$localizacion"/>
                {$erroresCampos['localizacion']}
            </div>
            <div>
                <button type="submit" name="modificar">Modificar Evento</button>
            </div>
        </fieldset>
        HTML;

        return $html;
    }

    protected function procesaFormulario(&$datos) {
        $this->errores = [];

        $imagen = $_FILES['imagen'];

        if ($imagen['error'] !== UPLOAD_ERR_OK) {
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
        if ( ! $horaIni ) {
            $this->errores['horaIni'] = 'El nombre del evento tiene que tener una longitud de al menos 5 caracteres.';
        }

        $horaFin = trim($datos['horaFin'] ?? '');
        $horaFin = filter_var($horaFin, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $horaFin) {
            $this->errores['horaFin'] = 'El nombre del evento tiene que tener una longitud de al menos 5 caracteres.';
        }

        $localizacion = trim($datos['localizacion'] ?? '');
        $localizacion = filter_var($localizacion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ( ! $localizacion || mb_strlen($localizacion) < 5) {
            $this->errores['localizacion'] = 'La localización debe tener una longitud de al menos 5 caracteres.';
        }

        if (count($this->errores) === 0) {
            Evento::actualiza($this->evento->getId(), $imagenBinaria, $nombreEvento, $fecha, $horaIni, $horaFin, $localizacion);
        }
    }

}
?>
