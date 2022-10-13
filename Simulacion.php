<?php
require_once './Clases/Tablero.php';
require_once './Auxiliar/ConexionEstatica.php';

function jugar($pos, &$tablero, &$tableroJug)
{
    $res = 0;
    $casilla = 0;
    for ($i = 0; $i < $tableroJug->getTam(); $i++) {
        if ($tableroJug[$i] == -2) {
            $casilla++;
        }
    }
    if ($casilla > 0) {
        if (!$tablero->hayMina()) {
            $tablero->Jugada($pos, $tableroJug);
            ConexionEstatica::modificarSituacionTablero($tablero, $tableroJug);
            $res = 1;
        } else {
            $tablero->setTerminado(true);
            ConexionEstatica::modificarSituacionTablero2($tablero);
            $res = 2;
        }
    } else {
        $tablero->setTerminado(true);
        ConexionEstatica::modificarSituacionTablero2($tablero);
        $res = 3;
    }
    return $res;
}
