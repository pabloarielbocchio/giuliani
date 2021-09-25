<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class Ot_listadosModel {
    private static $instancia;
    
    private $conn;

    public function __construct() {
        try {                
            $this->conn = Conexion::singleton_conexion();
        } catch ( Exception $e ) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_listados() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_listados(){
        try {
            $sql = "SELECT count(*) FROM orden_trabajos;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result[0];
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $estado){
        try {
            $desde = ($pagina - 1) * $registros;
            $sql = " SELECT *, (select descripcion from prioridades where codigo = orden_trabajos.prioridad) as desc_prioridad FROM orden_trabajos WHERE 1 = 1 ";
            if ($estado == -1){
                $sql .= " and finalizada = " . intval($estado);
            }
            if ($estado == 2){
                $sql .= " and finalizada = " . intval($estado);
            }
            if ($estado == 1){
                $sql .= " and finalizada = " . intval($estado);
            }
            if ($estado == 0){
                $sql .= " and (finalizada is null or finalizada = " . intval($estado) . ")";
            }
            $sql.= " and (nro_serie like '%" . $busqueda . "%' or observaciones like '%" . $busqueda . "%' or cliente like '%" . $busqueda . "%') ORDER BY " . $orderby . " " . $sentido;
            if (intval($registros) > 0){
                $sql_limit = $sql . " limit " . $desde . "," . $registros . ";";
            } else {
                $sql_limit = $sql ;
            }
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                $_SESSION["totales"] = count($result);
            }
            $query = $this->conn->prepare($sql_limit);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
        
    }
    
    public function deleteOt_listado($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from orden_trabajos where codigo = ?');
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return 1;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function addOt_listado($nroserie, $cliente, $prioridad, $fecha, $entrega, $observaciones){
        $hoy = date("Y-m-d H:i:s");
        if (date("Y", strtotime($entrega)) < 1980){
            $entrega = null;
        }
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos (nro_serie, cliente, fecha, fecha_entrega, observaciones, prioridad, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $nroserie, PDO::PARAM_STR);
            $stmt->bindValue(2, $cliente, PDO::PARAM_STR);
            $stmt->bindValue(3, $fecha, PDO::PARAM_STR);
            $stmt->bindValue(4, $entrega, PDO::PARAM_STR);
            $stmt->bindValue(5, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(6, $prioridad, PDO::PARAM_INT);
            $stmt->bindValue(7, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(8, $hoy, PDO::PARAM_STR);
            
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return var_dump($stmt->errorInfo());
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function abrirOt_listado($codigo){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos set '
                                            . 'finalizada = 0 , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');  
            $stmt->bindValue(1, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(2, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(3, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return 1;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function estadoOt_listado($codigo, $estado, $avance){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos set '
                                            . 'finalizada = ? , '
                                            . 'avance = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');  
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $avance, PDO::PARAM_STR);
            $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(5, $codigo, PDO::PARAM_INT);
            $observaciones = "Avance al " . number_format($avance,2) . "%, estado: ";
            if ($estado == 1){
                $observaciones .= "FINALIZADA";
            } elseif ($estado == 0){
                $observaciones .= "EN COLA";
            } elseif ($estado == -1){
                $observaciones .= "CANCELADA";
            } elseif ($estado == 2){
                $observaciones .= "EN CURSO";
            } 

            if($stmt->execute()){
                $this->addOt_evento(0, 0, 6, 0, $observaciones, $codigo);
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return 1;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function finalizarOt_listado($codigo){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos set '
                                            . 'finalizada = 1 , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');  
            $stmt->bindValue(1, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(2, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(3, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return 1;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function abrirOtDetalle_listado($codigo){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos_detalles set '
                                            . 'finalizada = 0 , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');  
            $stmt->bindValue(1, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(2, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(3, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return 1;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function finalizarOtDetalle_listado($codigo){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos_detalles set '
                                            . 'finalizada = 1 , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');  
            $stmt->bindValue(1, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(2, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(3, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return 1;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function updateOt_listado($codigo, $nroserie, $cliente, $prioridad, $fecha, $entrega, $observaciones){
        $hoy = date("Y-m-d H:i:s"); 
        if (date("Y", strtotime($entrega)) < 1980){
            $entrega = null;
        }
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos set '
                                            . 'nro_serie = ? , '
                                            . 'cliente = ? , '
                                            . 'fecha = ? , '
                                            . 'fecha_entrega = ? , '
                                            . 'observaciones = ? , '
                                            . 'prioridad = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');                  
            $stmt->bindValue(1, $nroserie, PDO::PARAM_STR);
            $stmt->bindValue(2, $cliente, PDO::PARAM_STR);
            $stmt->bindValue(3, $fecha, PDO::PARAM_STR);
            $stmt->bindValue(4, $entrega, PDO::PARAM_STR);
            $stmt->bindValue(5, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(6, $prioridad, PDO::PARAM_INT);
            $stmt->bindValue(7, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(8, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(9, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return var_dump($stmt->errorInfo());
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function getOt_listado($codigo){
        try {
            $sql = "SELECT * FROM orden_trabajos WHERE codigo = " . $codigo . ";";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
        
    public function getPrioridades(){
        try {
            $sql = "SELECT * FROM prioridades ORDER BY prioridad;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
    
    public function addOt_evento($detalle, $produccion, $evento, $destino, $observaciones, $ot_id = 0){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_eventos (evento_id, ot_id, ot_detalle_id, ot_produccion_id, destino_id, observaciones, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $evento, PDO::PARAM_INT);
            $stmt->bindValue(2, $ot_id, PDO::PARAM_INT);
            $stmt->bindValue(3, $detalle, PDO::PARAM_INT);
            $stmt->bindValue(4, $produccion, PDO::PARAM_INT);
            $stmt->bindValue(5, $destino, PDO::PARAM_INT);
            $stmt->bindValue(6, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(7, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(8, $hoy, PDO::PARAM_STR);
            
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return var_dump($stmt->errorInfo());
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
}	
?>