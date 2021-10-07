<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class DestinosModel {  
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

    public static function singleton_destinos() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountDestinos(){
        try {
            $sql = "SELECT count(*) FROM destinos;";
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
            $sql = "SELECT * FROM destinos WHERE descripcion like '%" . $busqueda . "%' ORDER BY " . $orderby . " " . $sentido;
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
    
    public function deleteDestino($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from destinos where codigo = ? AND ( codigo NOT IN (SELECT ad.destino_id FROM archivo_destinos ad) AND codigo NOT IN (SELECT ad.destino_id FROM usuarios_destinos ad) AND codigo NOT IN (SELECT ad.destino_id FROM orden_trabajos_estados ad) )');
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
    
    public function addDestino($descripcion, $visible, $orden){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO destinos (descripcion, orden, siempre_visible, usuario_m, fecha_m) VALUES (?,?,?,?,?);');
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $orden, PDO::PARAM_INT);
            $stmt->bindValue(3, $visible, PDO::PARAM_INT);
            $stmt->bindValue(4, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(5, $hoy, PDO::PARAM_STR);
            
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
    
    public function updateDestino($codigo, $descripcion, $visible, $orden){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE destinos set '
                                            . 'descripcion = ? , '
                                            . 'orden = ? , '
                                            . 'siempre_visible = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $orden, PDO::PARAM_INT);
            $stmt->bindValue(3, $visible, PDO::PARAM_INT);
            $stmt->bindValue(4, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(5, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(6, $codigo, PDO::PARAM_INT);
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
    
    public function getDestino($codigo){
        try {
            $sql = "SELECT * FROM destinos WHERE codigo = " . $codigo . ";";
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