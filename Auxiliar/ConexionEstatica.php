<?php
class ConexionEstatica
{
    private static $conexion;

    static function abrirConexion()
    {
        try {
            self::$conexion = new mysqli(Constantes::$ruta, Constantes::$usuario, Constantes::$password, Constantes::$bbdd);
        } catch (Exception $e) {
            die();
        }
    }
    static function cerrarConexion()
    {
        self::$conexion->close();
    }


    static function insertarSituacionTablero($tablero, $tableroJug)
    {
        $cad = '';
        for ($i = 0; $i < count($tablero); $i++) {
            if ($tablero[$i] == -1) {
                $cad = $cad . '#BUM';
            } else {
                $cad = $cad . '#' . $tablero[$i];
            }
        }
        $cadena = '';
        for ($i = 0; $i < count($tableroJug); $i++) {
            if ($tablero[$i] == -1) {
                $cadena = $cadena . '#---';
            } else {
                $cadena = $cadena . '#' . $tablero[$i];
            }
        }
        $query = "INSERT INTO " . Constantes::$tablaTablero . "(Id,Terminado,TableroOculto,TableroJug,Tam,Minas) VALUES (?,?,?,?,?,?)";
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("sbssii", $tablero->getcodigo(), $tablero->getTerminado(), $cad, $cadena, $tablero->getTam(), $tablero->getMinas());
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    static function modificarSituacionTablero($tablero, $tableroJug)
    {
        $cad = '';
        for ($i = 0; $i < count($tablero); $i++) {
            if ($tablero[$i] == -1) {
                $cad = $cad . '#BUM';
            } else {
                $cad = $cad . '#' . $tablero[$i];
            }
        }
        $cadena = '';
        for ($i = 0; $i < count($tableroJug); $i++) {
            if ($tablero[$i] == -1) {
                $cadena = $cadena . '#---';
            } else {
                $cadena = $cadena . '#' . $tablero[$i];
            }
        }
        $filasAfectadas = 0;
        $query = "UPDATE " . Constantes::$tablaTablero . " set Id  = ?,Terminado=?, TableroOculto=?, TableroJug = ?  WHERE Id = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("sbsss", $tablero->getCodigo(), $tablero->getTerminado(), $cad, $cadena, $tablero->getCodigo());
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    static function modificarSituacionTablero2($tablero)
    {
        $filasAfectadas = 0;
        $query = "UPDATE " . Constantes::$tablaTablero . " set Id  = ?,Terminado=?  WHERE Id = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("sbs", $tablero->getCodigo(), $tablero->getTerminado(), $tablero->getCodigo());
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }

    static function getUltimoTablero()
    {
        $t = null;
        $query = "Select * From " . Constantes::$tablaTablero . " Where Terminado = 0";
        self::abrirConexion();
        $stmt = self::$conexion->prepare($query);
        $stmt->execute();
        $resultados = $stmt->get_result();
        try {
            if ($resultados->num_rows != 0) {
                while ($fila = $resultados->fetch_array()) {
                    $tableroOculto = explode('#', $fila[2]);
                    for ($i = 0; $i < count($tableroOculto); $i++) {
                        if ($tableroOculto[$i] == 'BUM') {
                            $tableroOculto[$i] == -1;
                        }
                    }
                    $tableroJug = explode('#', $fila[3]);
                    for ($i = 0; $i < count($tableroJug); $i++) {
                        if ($tableroJug[$i] == '---') {
                            $tableroJug[$i] == -2;
                        }
                    }
                    $t = new Tablero($fila[0], $fila[1], $tableroOculto, $fila[4], $fila[5]);
                }
            }
        } catch (Exception $e) {
            $t = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            $resultados->free_result();
            self::cerrarConexion();
        }
        return $t;
    }
    static function getTableroJug()
    {
        $t = null;
        $query = "Select * From " . Constantes::$tablaTablero . " Where Terminado = 0";
        self::abrirConexion();
        $stmt = self::$conexion->prepare($query);
        $stmt->execute();
        $resultados = $stmt->get_result();
        try {
            if ($resultados->num_rows != 0) {
                while ($fila = $resultados->fetch_array()) {
                   
                    $tableroJug = explode('#', $fila[3]);
                    for ($i = 0; $i < count($tableroJug); $i++) {
                        if ($tableroJug[$i] == '---') {
                            $tableroJug[$i] == -2;
                        }
                    }
                    $t = new Tablero($fila[0], $fila[1], $tableroJug, $fila[4], $fila[5]);
                }
            }
        } catch (Exception $e) {
            $t = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            $resultados->free_result();
            self::cerrarConexion();
        }
        return $t;
    }

    static function eliminarTablero($id)
    {
        $cod = 0;
        $query = "Select * From " . Constantes::$tablaTablero . " Where Id = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $cod = $stmt->affected_rows;
        } catch (Exception $e) {
            $cod = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $cod;
    }
}
