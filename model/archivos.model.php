<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class ArchivosModel {  
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

    public static function singleton_archivos() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountArchivos(){
        try {
            $sql = "SELECT count(*) FROM archivos;";
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
            $sql = "SELECT * FROM archivos WHERE descripcion like '%" . $busqueda . "%' ORDER BY " . $orderby . " " . $sentido;
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
    
    public function deleteArchivoOtp($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivos where codigo = (SELECT ad.archivo_id FROM orden_trabajos_archivos ad where ad.ot_produccion_id = ? )');
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
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
    
    public function deleteArchivoOtd($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivos where codigo = (SELECT ad.archivo_id FROM orden_trabajos_archivos ad where ad.ot_detalle_id = ? )');
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
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
    
    public function deleteArchivoOt($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivos where codigo = (SELECT ad.archivo_id FROM orden_trabajos_archivos ad where ad.ot_id = ? )');
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
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
    
    public function deleteArchivo($codigo){
        try {
            $archivo = $this->getArchivo($codigo)[0];
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivos where codigo = ? AND ( codigo NOT IN (SELECT ad.archivo_id FROM orden_trabajos_archivos ad) )');
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                unlink("../".$archivo["ruta"]);
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

    public function deleteArchivo_destino($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivo_destinos where archivo_id = ?');
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

    public function deleteArchivo_destinoOt($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivo_destinos where archivo_id = (SELECT ad.archivo_id FROM orden_trabajos_archivos ad where ad.ot_id = ? )');
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

    public function deleteArchivo_destinoOtd($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivo_destinos where archivo_id = (SELECT ad.archivo_id FROM orden_trabajos_archivos ad where ad.ot_detalle_id = ? )');
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

    public function deleteArchivo_destinoOtp($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivo_destinos where archivo_id = (SELECT ad.archivo_id FROM orden_trabajos_archivos ad where ad.ot_produccion_id = ? )');
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

    public function deleteArchivo_ot($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from orden_trabajos_archivos where codigo = ?');
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

    public function deleteArchivo_otp($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from orden_trabajos_archivos where archivo_id = ?');
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
    
    public function addArchivo($descripcion, $ruta, $fecha_hora, $activo, $cod_prod_na, $cod_prod_nb, $cod_prod_nc, $cod_prod_nd, $cod_prod_ne, $cod_prod_nf, $cod_prod_personalizado_id, $cod_prod_estandar_id, $cod_ot_detalle_id, $cod_ot){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO archivos (descripcion, ruta, fecha_hora, activo, cod_prod_na, cod_prod_nb, cod_prod_nc, cod_prod_nd, cod_prod_ne, cod_prod_nf, cod_prod_personalizado_id, cod_prod_estandar_id, cod_ot_detalle_id, cod_ot, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $ruta, PDO::PARAM_STR);
            $stmt->bindValue(3, $fecha_hora, PDO::PARAM_STR);
            $stmt->bindValue(4, $activo, PDO::PARAM_INT);
            $stmt->bindValue(5, $cod_prod_na, PDO::PARAM_INT);
            $stmt->bindValue(6, $cod_prod_nb, PDO::PARAM_INT);
            $stmt->bindValue(7, $cod_prod_nc, PDO::PARAM_INT);
            $stmt->bindValue(8, $cod_prod_nd, PDO::PARAM_INT);
            $stmt->bindValue(9, $cod_prod_ne, PDO::PARAM_INT);
            $stmt->bindValue(10, $cod_prod_nf, PDO::PARAM_INT);
            $stmt->bindValue(11, $cod_prod_personalizado_id, PDO::PARAM_INT);
            $stmt->bindValue(12, $cod_prod_estandar_id, PDO::PARAM_INT);
            $stmt->bindValue(13, $cod_ot_detalle_id, PDO::PARAM_INT);
            $stmt->bindValue(14, $cod_ot, PDO::PARAM_INT);
            $stmt->bindValue(15, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(16, $hoy, PDO::PARAM_STR);
            
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
    
    public function updateArchivo($codigo, $descripcion, $ruta, $fecha_hora, $activo, $cod_prod_na, $cod_prod_nb, $cod_prod_nc, $cod_prod_nd, $cod_prod_ne, $cod_prod_nf, $cod_prod_personalizado_id, $cod_prod_estandar_id, $cod_ot_detalle_id){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE archivos set '
                                            . 'descripcion = ? , '
                                            . 'ruta = ? , '
                                            . 'fecha_hora = ? , '
                                            . 'activo = ? , '
                                            . 'cod_prod_na = ? , '
                                            . 'cod_prod_nb = ? , '
                                            . 'cod_prod_nc = ? , '
                                            . 'cod_prod_nd = ? , '
                                            . 'cod_prod_ne = ? , '
                                            . 'cod_prod_nf = ? , '
                                            . 'cod_prod_personalizado_id = ? , '
                                            . 'cod_prod_estandar_id = ? , '
                                            . 'cod_ot_detalle_id = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $ruta, PDO::PARAM_STR);
            $stmt->bindValue(3, $fecha_hora, PDO::PARAM_STR);
            $stmt->bindValue(4, $activo, PDO::PARAM_INT);
            $stmt->bindValue(5, $cod_prod_na, PDO::PARAM_INT);
            $stmt->bindValue(6, $cod_prod_nb, PDO::PARAM_INT);
            $stmt->bindValue(7, $cod_prod_nc, PDO::PARAM_INT);
            $stmt->bindValue(8, $cod_prod_nd, PDO::PARAM_INT);
            $stmt->bindValue(9, $cod_prod_ne, PDO::PARAM_INT);
            $stmt->bindValue(10, $cod_prod_nf, PDO::PARAM_INT);
            $stmt->bindValue(11, $cod_prod_personalizado_id, PDO::PARAM_INT);
            $stmt->bindValue(12, $cod_prod_estandar_id, PDO::PARAM_INT);
            $stmt->bindValue(13, $cod_ot_detalle_id, PDO::PARAM_INT);
            $stmt->bindValue(14, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(15, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(16, $codigo, PDO::PARAM_INT);
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
    
    public function getArchivo($codigo){
        try {
            $sql = "SELECT * FROM archivos WHERE codigo = " . $codigo . ";";
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
    
    public function getProductosA(){
        try {
            $sql = "SELECT * FROM productos_nivel_a order by descripcion desc;";
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
    
    public function getProductosB(){
        try {
            $sql = "SELECT * FROM productos_nivel_b order by descripcion desc;";
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
    
    public function getProductosC(){
        try {
            $sql = "SELECT * FROM productos_nivel_c order by descripcion desc;";
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
            $sql = "SELECT * FROM productos_nivel_d order by descripcion desc;";
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
    
    public function getProductosE(){
        try {
            $sql = "SELECT * FROM productos_nivel_e order by descripcion desc;";
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
    
    public function getProductosF(){
        try {
            $sql = "SELECT * FROM productos_nivel_f order by descripcion desc;";
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
    
    public function getProductosS(){
        try {
            $sql = "SELECT * FROM productos_estandar order by descripcion;";
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
    
    public function getProductosP(){
        try {
            $sql = "SELECT * FROM productos_personalizados order by descripcion;";
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
    
    public function getLastArchivo(){
        try {
            $sql = "SELECT * FROM archivos order by codigo desc;";
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
    
    public function addArchivo_produccion($archivo, $produccion, $observaciones, $detalle, $ot){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_archivos (archivo_id, ot_produccion_id, observaciones, ot_detalle_id, ot_id, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?);');
            
            $stmt->bindValue(1, $archivo, PDO::PARAM_INT);
            $stmt->bindValue(2, $produccion, PDO::PARAM_INT);
            $stmt->bindValue(3, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(4, $detalle, PDO::PARAM_INT);
            $stmt->bindValue(5, $ot, PDO::PARAM_INT);
            $stmt->bindValue(6, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(7, $hoy, PDO::PARAM_STR);
            
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
    
    public function getDestinos(){
        try {
            $sql = "SELECT * FROM destinos order by descripcion;";
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
    
    public function addArchivo_destino($archivo, $destino, $observaciones){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO archivo_destinos (archivo_id, destino_id, observaciones, usuario_m, fecha_m) VALUES (?,?,?,?,?);');
            
            $stmt->bindValue(1, $archivo, PDO::PARAM_INT);
            $stmt->bindValue(2, $destino, PDO::PARAM_INT);
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
}	
?>