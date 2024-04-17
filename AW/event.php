<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/SA/EventoSA.php';
require_once __DIR__.'/TO/Tarifa.php';
require_once __DIR__.'/includes/helpers/evento.php';

if (!isset($_SESSION["login"])) {
    //Si no esta logueado
	header("Location: login.php");
    exit;
} else {

    $id = $_GET['id'];
    $puedoComprar = !$_SESSION['esAdmin'];
    $eventoYTarifa = EventoSA::obtenerEvento($id);
    $evento = $eventoYTarifa[0];
    $tarifas = $eventoYTarifa[1];

    $tituloCabecera = $evento->getNombreEvento();
    $tituloPagina = $evento->getNombreEvento();
    $padding = "25%";

    if ($evento->getIdPromotor() == $_SESSION["id"]) {
    
        //Formateo de la fecha
        $fecha = $evento->getFecha();
        setlocale(LC_TIME,'es_ES.utf8','es_ES', 'esp');
        $fechaFormateada = ucfirst(strftime("%A, %e %B", strtotime($fecha)));

        $horaIni = $evento->getHoraIni(); //obtenemos la hora inicial
        $horaFin = $evento->getHoraFin(); //Obtenemos la hora final
        $localizacion = $evento->getLocalizacion(); //Obtenemos la localización
        $recurso = "data:image/jpeg;base64," . $evento->getImagen(); //Obtenemos la imagen
        
        $contenidoPrincipal = <<<HTML
        <img src={$recurso} class="evento-imagen">
        <div class="evento-info">
            <div style="display: flex; gap: 5px; align-items: center">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.8193 0.875C14.1816 0.875 14.4756 1.169 14.4756 1.53125L14.476 2.27308C15.7536 2.36068 16.8146 2.79829 17.5656 3.55084C18.3855 4.37421 18.8169 5.55809 18.8125 6.97821V14.9608C18.8125 17.8763 16.961 19.6876 13.9816 19.6876H6.58087C3.6015 19.6876 1.75 17.851 1.75 14.8943V6.97646C1.75 4.22651 3.40117 2.46132 6.0941 2.27338L6.09464 1.53125C6.09464 1.169 6.38864 0.875 6.75089 0.875C7.11314 0.875 7.40714 1.169 7.40714 1.53125L7.40688 2.25663H13.1626L13.1631 1.53125C13.1631 1.169 13.4571 0.875 13.8193 0.875ZM17.5 8.666H3.0625V14.8943C3.0625 17.1396 4.312 18.3751 6.58087 18.3751H13.9816C16.2505 18.3751 17.5 17.1623 17.5 14.9608L17.5 8.666ZM14.176 14.1718C14.5383 14.1718 14.8323 14.4658 14.8323 14.828C14.8323 15.1903 14.5383 15.4843 14.176 15.4843C13.8138 15.4843 13.5163 15.1903 13.5163 14.828C13.5163 14.4658 13.8059 14.1718 14.1682 14.1718H14.176ZM10.2932 14.1718C10.6555 14.1718 10.9495 14.4658 10.9495 14.828C10.9495 15.1903 10.6555 15.4843 10.2932 15.4843C9.93099 15.4843 9.63349 15.1903 9.63349 14.828C9.63349 14.4658 9.92311 14.1718 10.2854 14.1718H10.2932ZM6.40229 14.1718C6.76454 14.1718 7.05854 14.4658 7.05854 14.828C7.05854 15.1903 6.76454 15.4843 6.40229 15.4843C6.04004 15.4843 5.74166 15.1903 5.74166 14.828C5.74166 14.4658 6.03216 14.1718 6.39441 14.1718H6.40229ZM14.176 10.7709C14.5383 10.7709 14.8323 11.0649 14.8323 11.4272C14.8323 11.7894 14.5383 12.0834 14.176 12.0834C13.8138 12.0834 13.5163 11.7894 13.5163 11.4272C13.5163 11.0649 13.8059 10.7709 14.1682 10.7709H14.176ZM10.2932 10.7709C10.6555 10.7709 10.9495 11.0649 10.9495 11.4272C10.9495 11.7894 10.6555 12.0834 10.2932 12.0834C9.93099 12.0834 9.63349 11.7894 9.63349 11.4272C9.63349 11.0649 9.92311 10.7709 10.2854 10.7709H10.2932ZM6.40229 10.7709C6.76454 10.7709 7.05854 11.0649 7.05854 11.4272C7.05854 11.7894 6.76454 12.0834 6.40229 12.0834C6.04004 12.0834 5.74166 11.7894 5.74166 11.4272C5.74166 11.0649 6.03216 10.7709 6.39441 10.7709H6.40229ZM13.1626 3.56913H7.40688L7.40714 4.41088C7.40714 4.77313 7.11314 5.06713 6.75089 5.06713C6.38864 5.06713 6.09464 4.77313 6.09464 4.41088L6.09417 3.58899C4.13397 3.75366 3.0625 4.94187 3.0625 6.97646V7.3535H17.5L17.5 6.97646C17.5035 5.89584 17.213 5.05584 16.6364 4.47834C16.1302 3.97067 15.3902 3.66748 14.4763 3.58941L14.4756 4.41088C14.4756 4.77313 14.1816 5.06713 13.8193 5.06713C13.4571 5.06713 13.1631 4.77313 13.1631 4.41088L13.1626 3.56913Z"
                        fill="white"
                        fill-opacity="0.5"/>
                </svg>
                <p style="color: #979ca4">{$fechaFormateada}</p>
            </div>
            <div style="display: flex; gap: 5px; align-items: center">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10.5 1.75C15.3247 1.75 19.25 5.67525 19.25 10.5C19.25 15.3247 15.3247 19.25 10.5 19.25C5.67525 19.25 1.75 15.3247 1.75 10.5C1.75 5.67525 5.67525 1.75 10.5 1.75ZM10.5 3.0625C6.39888 3.0625 3.0625 6.39888 3.0625 10.5C3.0625 14.6011 6.39888 17.9375 10.5 17.9375C14.6011 17.9375 17.9375 14.6011 17.9375 10.5C17.9375 6.39888 14.6011 3.0625 10.5 3.0625ZM10.2035 6.20848C10.5667 6.20848 10.8598 6.50247 10.8598 6.86473V10.734L13.8392 12.5102C14.1498 12.6966 14.2522 13.0991 14.0667 13.4106C13.9433 13.6162 13.7254 13.7309 13.5023 13.7309C13.3877 13.7309 13.2722 13.7011 13.1663 13.639L9.86755 11.6711C9.6698 11.5521 9.5473 11.3377 9.5473 11.1067V6.86473C9.5473 6.50247 9.8413 6.20848 10.2035 6.20848Z"
                        fill="white"
                        fill-opacity="0.5"
                    />
                </svg>
                <p style="color: #979ca4">{$horaIni}-{$horaFin}</p>
            </div>
        </div>

        <div style="flex-direction: row;">
            <button class="delete-button" onclick="location.href='deleteEvent.php?id={$id}'"><h4>Delete Event<h4></button>
            <button class="modify-button" onclick="location.href='modifyEvent.php?id={$id}'"><h4>Modify Event<h4></button>
        </div>

        <h2>Entradas</h2>
            <ul class="tarifas">
        HTML;

        if (empty($tarifas)) {
            $contenidoPrincipal .= "<li>No hay tarifas disponibles</li>";
        } else {
            foreach ($tarifas as $tarifa) {
                $contenidoPrincipal .= '<li>' . mostrarTarifa($tarifa) . '</li>';
            }
        }
        $contenidoPrincipal .= '</ul>';
    } else {
        //Si no es mio

        //Formateo de la fecha
        $fecha = $evento->getFecha();
        setlocale(LC_TIME,'es_ES.utf8','es_ES', 'esp');
        $fechaFormateada = ucfirst(strftime("%A, %e %B", strtotime($fecha)));

        $horaIni = $evento->getHoraIni();
        $horaFin = $evento->getHoraFin();
        $localizacion = $evento->getLocalizacion();
        $recurso = "data:image/jpeg;base64," . $evento->getImagen(); //Obtenemos la imagen
        
        $contenidoPrincipal = <<<HTML
        <img src={$recurso} class="evento-imagen">
        <div class="evento-info">
            <div style="display: flex; gap: 5px; align-items: center">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.8193 0.875C14.1816 0.875 14.4756 1.169 14.4756 1.53125L14.476 2.27308C15.7536 2.36068 16.8146 2.79829 17.5656 3.55084C18.3855 4.37421 18.8169 5.55809 18.8125 6.97821V14.9608C18.8125 17.8763 16.961 19.6876 13.9816 19.6876H6.58087C3.6015 19.6876 1.75 17.851 1.75 14.8943V6.97646C1.75 4.22651 3.40117 2.46132 6.0941 2.27338L6.09464 1.53125C6.09464 1.169 6.38864 0.875 6.75089 0.875C7.11314 0.875 7.40714 1.169 7.40714 1.53125L7.40688 2.25663H13.1626L13.1631 1.53125C13.1631 1.169 13.4571 0.875 13.8193 0.875ZM17.5 8.666H3.0625V14.8943C3.0625 17.1396 4.312 18.3751 6.58087 18.3751H13.9816C16.2505 18.3751 17.5 17.1623 17.5 14.9608L17.5 8.666ZM14.176 14.1718C14.5383 14.1718 14.8323 14.4658 14.8323 14.828C14.8323 15.1903 14.5383 15.4843 14.176 15.4843C13.8138 15.4843 13.5163 15.1903 13.5163 14.828C13.5163 14.4658 13.8059 14.1718 14.1682 14.1718H14.176ZM10.2932 14.1718C10.6555 14.1718 10.9495 14.4658 10.9495 14.828C10.9495 15.1903 10.6555 15.4843 10.2932 15.4843C9.93099 15.4843 9.63349 15.1903 9.63349 14.828C9.63349 14.4658 9.92311 14.1718 10.2854 14.1718H10.2932ZM6.40229 14.1718C6.76454 14.1718 7.05854 14.4658 7.05854 14.828C7.05854 15.1903 6.76454 15.4843 6.40229 15.4843C6.04004 15.4843 5.74166 15.1903 5.74166 14.828C5.74166 14.4658 6.03216 14.1718 6.39441 14.1718H6.40229ZM14.176 10.7709C14.5383 10.7709 14.8323 11.0649 14.8323 11.4272C14.8323 11.7894 14.5383 12.0834 14.176 12.0834C13.8138 12.0834 13.5163 11.7894 13.5163 11.4272C13.5163 11.0649 13.8059 10.7709 14.1682 10.7709H14.176ZM10.2932 10.7709C10.6555 10.7709 10.9495 11.0649 10.9495 11.4272C10.9495 11.7894 10.6555 12.0834 10.2932 12.0834C9.93099 12.0834 9.63349 11.7894 9.63349 11.4272C9.63349 11.0649 9.92311 10.7709 10.2854 10.7709H10.2932ZM6.40229 10.7709C6.76454 10.7709 7.05854 11.0649 7.05854 11.4272C7.05854 11.7894 6.76454 12.0834 6.40229 12.0834C6.04004 12.0834 5.74166 11.7894 5.74166 11.4272C5.74166 11.0649 6.03216 10.7709 6.39441 10.7709H6.40229ZM13.1626 3.56913H7.40688L7.40714 4.41088C7.40714 4.77313 7.11314 5.06713 6.75089 5.06713C6.38864 5.06713 6.09464 4.77313 6.09464 4.41088L6.09417 3.58899C4.13397 3.75366 3.0625 4.94187 3.0625 6.97646V7.3535H17.5L17.5 6.97646C17.5035 5.89584 17.213 5.05584 16.6364 4.47834C16.1302 3.97067 15.3902 3.66748 14.4763 3.58941L14.4756 4.41088C14.4756 4.77313 14.1816 5.06713 13.8193 5.06713C13.4571 5.06713 13.1631 4.77313 13.1631 4.41088L13.1626 3.56913Z"
                        fill="white"
                        fill-opacity="0.5"/>
                </svg>
                <p style="color: #979ca4">{$fechaFormateada}</p>
            </div>
            <div style="display: flex; gap: 5px; align-items: center">
                <svg width="21" height="21" viewBox="0 0 21 21" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10.5 1.75C15.3247 1.75 19.25 5.67525 19.25 10.5C19.25 15.3247 15.3247 19.25 10.5 19.25C5.67525 19.25 1.75 15.3247 1.75 10.5C1.75 5.67525 5.67525 1.75 10.5 1.75ZM10.5 3.0625C6.39888 3.0625 3.0625 6.39888 3.0625 10.5C3.0625 14.6011 6.39888 17.9375 10.5 17.9375C14.6011 17.9375 17.9375 14.6011 17.9375 10.5C17.9375 6.39888 14.6011 3.0625 10.5 3.0625ZM10.2035 6.20848C10.5667 6.20848 10.8598 6.50247 10.8598 6.86473V10.734L13.8392 12.5102C14.1498 12.6966 14.2522 13.0991 14.0667 13.4106C13.9433 13.6162 13.7254 13.7309 13.5023 13.7309C13.3877 13.7309 13.2722 13.7011 13.1663 13.639L9.86755 11.6711C9.6698 11.5521 9.5473 11.3377 9.5473 11.1067V6.86473C9.5473 6.50247 9.8413 6.20848 10.2035 6.20848Z"
                        fill="white"
                        fill-opacity="0.5"
                    />
                </svg>
                <p style="color: #979ca4">{$horaIni}-{$horaFin}</p>
            </div>
        </div>

        <h2>Entradas</h2>
        <div style="text-align: center">
            <ul class="tarifas">
        HTML;

        if (empty($tarifas)) {
            $contenidoPrincipal .= "<li>No hay tarifas disponibles</li>";
        } else {
            foreach ($tarifas as $tarifa) {
                $tarifaId = $tarifa->getId();// ID de la tarifa
                
                $selectedStyle = isset($_GET['tarifa']) && $_GET['tarifa'] == $tarifaId ? 'background-color: #2A323F;' : '';
                $url = "?id={$id}";
                if (isset($_GET['tarifa']) && $_GET['tarifa'] == $tarifaId) {
                    // Si la tarifa ya está seleccionada, eliminamos el parámetro de la URL al hacer clic nuevamente
                    $url = "?id={$id}";
                } else {
                    // Si la tarifa no está seleccionada, agregamos el parámetro de la URL al hacer clic
                    $url .= "&tarifa={$tarifaId}";
                }
                $contenidoPrincipal .= <<<EOS
                    <li>
                        <button class="tarifa" style="$selectedStyle" onclick="location.href='$url'">
                            <h3>{$tarifa->getConsumiciones()} x {$tarifa->getPrecio()}€</h3>
                            <p>{$tarifa->getInformacion()}</p>
                        </button>
                    </li>
                EOS;
            }
        }
        $contenidoPrincipal .= <<<EOS
        </ul>
        EOS;

        if (isset($_GET['tarifa'])) {
            $contenidoPrincipal .= <<<EOS
                <button class="next-button" onclick="location.href='asistEvent.php?id={$id}&tarifa={$tarifaId}'"><h4>Comprar</h4></button>;
            </div>
            EOS;
        }
    }
}

require __DIR__.'/includes/vistas/plantillas/plantilla.php';
