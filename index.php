<?php
require_once './Clases/Tablero.php';
require_once './Factoria.php';
require_once './Simulacion.php';

header("Content-Type:application/json");

$requestMethod = $_SERVER["REQUEST_METHOD"];
$paths = $_SERVER['REQUEST_URI'];
$parametro = explode('/', $paths);
unset($parametro[0]);
$t = ConexionEstatica::getUltimoTablero();
if ($requestMethod == "GET" && $parametro[1] == 'jugarconnivel' && !empty($parametro[2]) && $t == null) {
    if (!is_numeric($parametro[2])) {
        $t = Factoria::crearTablero($parametro[2]);
        $tj = $t;
        $t->formarTableroOculto();
        $tj->formarTableroJug();
        ConexionEstatica::insertarSituacionTablero($t, $tj);
    } else {
        $cod = 400;
        $desc = 'Escribe el nivel que quieres:superfacil, facil, normal, dificil, imposible';
    }
}
if ($requestMethod == "GET" && $parametro[1] == 'retirada') {
    $t = ConexionEstatica::getUltimoTablero();
    if (ConexionEstatica::eliminarTablero($t->getID()) > 0) {
        $cod = 200;
        $desc = 'Tablero eliminado';
    } else {
        $cod = 400;
        $desc = 'No se pudo eliminar el tablero';
    }
}
if ($requestMethod == "GET" && $parametro[1] == 'jugar' && !empty($parametro[2]) && !empty($parametro[3]) && $t == null) {
    if (is_numeric($parametro[2]) && is_numeric($parametro[3])) {
        if ($parametro[2] < $parametro[3] || $parametro[2] > 100) {
            $cod = 400;
            $desc = 'Revisa los parametros';
        } else {
            $t = Factoria::crearTableroPersonalizado($parametro[2], $parametro[3]);
            $tj = $t;
            $t->formarTableroOculto();
            $tj->formarTableroJug();
            ConexionEstatica::insertarSituacionTablero($t, $tj);
        }
    } else {
        $cod = 400;
        $desc = 'Comprueba los parametros que estas mandando';
    }
}
if ($requestMethod == "GET" && $parametro[1] == 'jugar' && !empty($parametro[2]) && $t != null) {
    if (is_numeric($parametro[2])) {
        if ($parametro[2] >= 0 || $parametro[2] < $t->getTam()) {
            $tj = ConexionEstatica::getTableroJug();
            $respuesta = jugar($parametro[2], $t, $tj);
            if ($respuesta == 3) {
                $cod = 200;
                $desc = 'Felicidades has ganado';
                $mensajeAdicional = ['Tablero' => $t];
            } else {
                if ($respuesta == 2) {
                    $cod = 200;
                    $desc = 'Lo siento pero has pillado una bomba';
                    $mensajeAdicional = ['Tablero' => $t];
                } else {
                    if ($respuesta == 1) {
                        $cod = 200;
                        $desc = 'No has dado ha ninguna bomba';
                        $mensajeAdicional = ['Tablero' => $tj];
                    }
                }
            }
        } else {
            $cod = 400;
            $desc = 'Recuerda las posiciones van de 0 a ' . ($t->getTam() - 1);
        }
    } else {
        $cod = 400;
        $desc = 'Escribe un numero';
    }
} else {
    $cod = 400;
    $desc = 'Comprueba los parametros que estas mandando';
}

if ($requestMethod != "GET") {
    $cod = 400;
    $desc = 'Metodo incorrecto';
}

header("HTTP/1.1 " . $cod . " " . $desc);
$mensaje = [
    'cod' => $cod,
    'desc' => $desc
];
if (isset($mensajeAdicional)) {
    $mensaje = array_merge($mensaje, $mensajeAdicional);
}
echo json_encode($mensaje, JSON_UNESCAPED_UNICODE);
