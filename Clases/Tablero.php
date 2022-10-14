<?php
class Tablero
{
    public $tablero;
    public $minas;
    public $tam;
    public $codigo;
    public static $ID;

    public function __construct()
    {
        $argumentos = func_get_args();
        $nArg = func_num_args();
        if (method_exists($this, $metodo = '__construct' . $nArg)) {
            call_user_func_array(array($this, $metodo), $argumentos);
        }
    }
    private function __construct2($tam, $minas)
    {
        $this->minas = $minas;
        $this->tam = $tam;
        $this->tablero = [];
        self::$ID++;
        $this->codigo = self::$ID . 'A';
    }
    private function __construct4($id, $tablero, $tam, $minas)
    {
        $this->minas = $minas;
        $this->tam = $tam;
        $this->tablero = $tablero;
        $this->codigo = $id;
    }


    function __toString()
    {
        $cad = "";
        for ($i = 0; $i < $this->tam; $i++) {
            if ($this->tablero[$i] == -1) {
                $cad = $cad . ' BUM ';
            } else {
                $cad = $cad . $this->tablero[$i] . ' ';
            }
        }
        return $cad;
    }
    function mostrarTablero()
    {
        $cad = "";
        for ($i = 0; $i < $this->tam; $i++) {
            if ($this->tablero[$i] === -2) {
                $cad = $cad . ' --- ';
            } else {
                $cad = $cad . ' ' . $this->tablero[$i] . ' ';
            }
        }
        return $cad;
    }
    function Jugada($pos, $tablero, $tableroJugador)
    {
        $tableroJugador[$pos] = $tablero[$pos];
    }
    function formarTableroJug()
    {
        for ($i = 0; $i < $this->tam; $i++) {
            $this->tablero[$i] = -2;
        }
        return $this->tablero;
    }

    function formarTableroOculto()
    {
        for ($i = 0; $i < $this->tam; $i++) {
            $this->tablero[$i] = 0;
        }
        $this->colocarMina();
        $this->ColocarPista();
        return $this->tablero;
    }

    function colocarMina()
    {
        while ($this->minas > 0) {
            $alea = rand(0, (($this->tam) - 1));
            if ($this->tablero[$alea] != -1) {
                $this->tablero[$alea] = -1;
                $this->minas--;
            }
        }
    }
    function HayMina($pos)
    {
        return $this->tablero[$pos] == -1;
    }
    function ColocarPista()
    {
        for ($i = 0; $i < $this->tam; $i++) {
            if ($this->tablero[$i] == -1) {
                if ($i - 1 >= 0) {
                    if ($this->tablero[$i - 1] != -1) {
                        $this->tablero[$i - 1]++;
                    }
                }
                if ($i + 1 < $this->tam) {
                    if ($this->tablero[$i + 1] != -1) {
                        $this->tablero[$i + 1]++;
                    }
                }
            }
        }
    }

    public function getTerminado()
    {
        return $this->terminado;
    }

    public function setTerminado($terminado)
    {
        $this->terminado = $terminado;

        return $this;
    }

    public function getID()
    {
        return $this->ID;
    }
    public function obtenerTam(){
        return count($this->tablero);
    }

    public function getCodigo()
    {
        return $this->codigo;
    }
    public function getMinas()
    {
        return $this->minas;
    }
    public function obtenerValorTablero($i)
    {
        return $this->tablero[$i];
    }

    public function getTam()
    {
        return $this->tam;
    }
}
