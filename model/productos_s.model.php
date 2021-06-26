<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class ProductosModel {  
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

    public static function singleton_productos() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountProductos(){
        try {
            $sql = "SELECT count(*) FROM productos_estandar;";
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
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        try {
            $desde = ($pagina - 1) * $registros;
            $sql = "SELECT * FROM productos_estandar WHERE descripcion like '%" . $busqueda . "%' ORDER BY " . $orderby . " " . $sentido;
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
    
    public function deleteProducto($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from productos_estandar where codigo = ?');
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
    
    public function addProducto($descripcion, $prodd){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO productos_estandar (descripcion, cod_prod_nd, usuario_m, fecha_m) VALUES (?,?,?,?);');
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $prodd, PDO::PARAM_INT);
            $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            
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
    
    public function updateProducto($codigo, $descripcion, $prodd){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE productos_estandar set '
                                            . 'descripcion = ? , '
                                            . 'cod_prod_nd = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $prodd, PDO::PARAM_INT);
            $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(5, $codigo, PDO::PARAM_INT);
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
    
    public function getProducto($codigo){
        try {
            $sql = "SELECT * FROM productos_estandar WHERE codigo = " . $codigo . ";";
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
    
    public function getUnidades(){
        try {
            $sql = "SELECT * FROM unidades order by descripcion;";
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
    
    public function getProductosD(){
        try {
            $sql = "SELECT * FROM productos_nivel_d order by descripcion;";
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
    
    public function addProductoConf($prodf, $prods){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO productos_configuraciones (prod_f_id, prod_standar_id, usuario_m, fecha_m) VALUES (?,?,?,?);');
            $stmt->bindValue(1, $prodf, PDO::PARAM_INT);
            $stmt->bindValue(2, $prods, PDO::PARAM_INT);
            $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            
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
    
    public function addOt_estado($produccion, $estado, $destino, $observaciones, $parametro){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_estados (estado_id, ot_prod_id, destino_id, observaciones, '.$parametro.', usuario_m, fecha_m) VALUES (?,?,?,?,1,?,?);');
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $produccion, PDO::PARAM_INT);
            $stmt->bindValue(3, $destino, PDO::PARAM_INT);
            $stmt->bindValue(4, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(5, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(6, $hoy, PDO::PARAM_STR);
            
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
    
    public function addArchivo_produccion($archivo, $produccion, $observaciones){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_archivos (archivo_id, ot_produccion_id, observaciones, usuario_m, fecha_m) VALUES (?,?,?,?,?);');
            
            $stmt->bindValue(1, $archivo, PDO::PARAM_INT);
            $stmt->bindValue(2, $produccion, PDO::PARAM_INT);
            $stmt->bindValue(3, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(4, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(5, $hoy, PDO::PARAM_STR);
            
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

    public function getLastProducto(){
        try {
            $sql = "SELECT * FROM productos_estandar order by codigo desc;";
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

    public function getLastOtp(){
        try {
            $sql = "SELECT * FROM orden_trabajos_produccion order by codigo desc;";
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
    
    public function addOt_produccion($avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_produccion (ot_detalle_id, prod_estandar_id, prod_personalizado_id, unidad_id, estado_id, prioridad_id, avance, cantidad, standar, observaciones, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $detalle, PDO::PARAM_INT);
            $stmt->bindValue(2, $prod_id, PDO::PARAM_INT);
            $stmt->bindValue(3, $personalizado_id, PDO::PARAM_INT);
            $stmt->bindValue(4, $unidad, PDO::PARAM_INT);
            $stmt->bindValue(5, $estado, PDO::PARAM_INT);
            $stmt->bindValue(6, $prioridad, PDO::PARAM_INT);
            $stmt->bindValue(7, $avance, PDO::PARAM_STR);
            $stmt->bindValue(8, $cantidad, PDO::PARAM_STR);
            $stmt->bindValue(9, $standard, PDO::PARAM_STR);
            $stmt->bindValue(10, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(11, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(12, $hoy, PDO::PARAM_STR);
            
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
    
    public function getProductosTabla($tabla, $codigo){
        try {
            $sql = "SELECT * FROM " . $tabla . " where codigo = " . intval($codigo) . " ;";
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
    
    public function getProductosArchivos($condicion, $codigo, $conditions){
        try {
            $sql = "SELECT * FROM archivos where activo = 1 " . $conditions . " and " . $condicion . " = " . intval($codigo) . " ;";
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
}	
?>