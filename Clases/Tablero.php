<?php
class Tablero
{
    public $tablero;
    public $minas;
    public $tam;
    public $terminado;
    public $codigo;
    public static $ID;

    public function __construct()
    {
        $agumentos = func_get_args();
        $nArg = func_num_args();
        if (method_exists($this, $metodo = '__construct' . $nArg)) {
            call_user_func_array(array($this, $metodo), $agumentos);
        }
    }
    private function __construct1($tam, $minas)
    {
        $this->minas = $minas;
        $this->tam = $tam;
        $this->tablero = [];
        $this->terminado = false;
        self::$ID++ . 'A';
        $this->codigo = self::$ID;
    }
    private function __construct5($id,$terminado,$tablero,$tam,$minas)
    {
        $this->minas = $minas;
        $this->tam = $tam;
        $this->tablero = $tablero;
        $this->terminado = $terminado;
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
            if ($this->tablero[$i] == -2) {
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
    function HayMina($pos){
        return $this->tablero[$pos]==-1;
    }
    function ColocarPista()
    {
        for ($i = 0; $i < count($this->tablero); $i++) {
            if ($this->tablero[$i] == -1) {
                if ($i - 1 >= 0){
                    if ($this->tablero[$i - 1] != -1){
                       $this->tablero[$i - 1]++;
                    }
                }
                if ($i + 1 < count($this->tablero)){
                    if ($this->tablero[$i + 1] != -1){
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
    public function getTam()
    {
        return $this->tam;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getMinas()
    {
        return $this->minas;
    }
}
