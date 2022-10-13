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
        $query = "INSERT INTO " . Constantes::$tablaTablero . "(Id, TableroOculto,TableroJug,Finalizado) VALUES (?,?,?,?)";
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("sisb", $tablero->getID(), $cad, $cadena, $tablero->getTerminado());
            $stmt->execute();
            $filasAfectadas = $stmt->affected_rows;
        } catch (Exception $e) {
            $filasAfectadas = ['codigo' => $e->getCode(), 'mensaje' => $e->getMessage()];
        } finally {
            self::cerrarConexion();
        }
        return $filasAfectadas;
    }
    static function modificarSituacionTablero($tablero, $tableroJug, $usuario)
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
        $query = "UPDATE " . Constantes::$tablaTablero . " set Id  = ?, TableroOculto=?, TableroJug = ?  WHERE Id = ?";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
            $stmt->bind_param("sissii", $tablero->getID(), $cad, $cadena, $tablero->getID());
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
                    $t = new Tablero($fila[1], $fila[2], $fila[3], $fila[4], $fila[5]);
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

    static function eliminarTablero()
    {
        $cod = 0;
        $query = "Select * From " . Constantes::$tablaTablero . " Where Terminado = 1";
        self::abrirConexion();
        try {
            $stmt = self::$conexion->prepare($query);
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
