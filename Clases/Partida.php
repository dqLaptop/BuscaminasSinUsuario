<?php
require_once __DIR__ . '/Tablero.php';
class Partida
{
    public $cod_tablero;
    public $cod_partida;
    public $tableroJugador;
    public $tableroOculto;
    public $terminada;

    function __construct($id, $id_tablero, $t, $tj, $terminada)
    {
        $this->cod_tablero = $id_tablero;
        $this->cod_partida = $id;
        $this->tableroJugador = $tj;
        $this->tableroOculto = $t;
        $this->terminada = $terminada;
    }
    function jugar($pos)
    {
        $res = 0;
        $casilla = 0;
        for ($i = 0; $i < $this->tableroOculto->getTam(); $i++) {
            if ($this->tableroOculto->obtenerValorTablero($i) === -1) {
                $casilla++;
            }
        }
        if ($this->casilla > 0) {
            if (!$this->tableroOculto->hayMina()) {
                $this->tableroOculto->Jugada($pos, $this->tableroJug);
                $cad = '';
                for ($i = 0; $i < $this->tableroOculto->getTam(); $i++) {
                    if ($this->tableroOculto->obtenerValorTablero($i) === -1) {
                        $cad = $cad . '#BUM';
                    } else {
                        $cad = $cad . '#' . $this->tableroOculto->obtenerValorTablero($i);
                    }
                }
                $cadena = '';
                for ($i = 0; $i < $this->tableroJug->getTam(); $i++) {
                    if ($this->tableroJug->obtenerValorTablero($i) === -1) {
                        $cadena = $cadena . '#---';
                    } else {
                        $cadena = $cadena . '#' . $this->tableroJug->obtenerValorTablero($i);
                    }
                }
                ConexionEstatica::modificarSituacionPartida($this,$cad,$cadena);
                $res = 1;
            } else {
                $this->terminada=1;
                ConexionEstatica::modificarSituacionPartida2($this);
                $res = 2;
            }
        } else {
            $this->terminada=1;
            ConexionEstatica::modificarSituacionPartida2($this);
            $res = 3;
        }
        return $res;
    }

    public function getTerminada()
    {
        return $this->terminada;
    }
    public function setTerminada($terminada)
    {
        $this->terminada = $terminada;

        return $this;
    }
    public function getCod_partida()
    {
        return $this->cod_partida;
    }

    public function getCod_tablero()
    {
        return $this->cod_tablero;
    }
    public function getTamanio()
    {
        return $this->tableroOculto->getTam();
    }

}
