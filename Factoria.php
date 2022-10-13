<?php
require_once './Clases/Tablero.php';
class Factoria
{
    static function crearTablero($nivel)
    {
        switch ($nivel) {
            case 'superfacil':
                $t = new Tablero(15, 1);
                break;
            case 'facil':
                $t = new Tablero(15, 3);
                break;
            case 'normal':
                $t = new Tablero(10, 3);
                break;
            case 'dificil':
                $t = new Tablero(10, 6);
                break;
            case 'imposible':
                $t = new Tablero(10, 9);
                break;
            default:
                $t = new Tablero(15, 5);
                break;
        }
        return $t;
    }
    static function crearTableroPersonalizado($tam, $minas)
    {
        return new Tablero($tam, $minas);
    }
}