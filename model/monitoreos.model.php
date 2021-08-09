<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

//include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";
include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion_utils.php";

class MonitoreosModel {
    private static $instancia;
    
    private $conn;

    public function __construct() {
        try {                
            //$this->conn = Conexion::singleton_conexion();
            $this->conn = ConexionUtils::singleton_conexion();
        } catch ( Exception $e ) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_monitoreos() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountMonitoreos(){
        try {
            $sql = "SELECT count(*) as cuenta FROM destinos;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = $result[0];
                return $result[0];
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return $error;
        }
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        try {
            $sql = "select 
                        m.*
                    from 
                        destinos m
                    order by
                        m.orden asc;";
            $sql_limit = $sql ;
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["totales"] = count($result);
            }
            $query = $this->conn->prepare($sql_limit);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = $result;
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return $error;
        }
        
    }
    
    public function getMonitoreosAll(){
        try {
            $sql = "SELECT 
                        monitoreos.*
                    FROM 
                        destinos monitoreos;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = $result;
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return $error;
        }
    }
    
    public function getRoles(){
        try {
            $sql = "SELECT 
                        roles.*
                    FROM 
                        roles
                    where 
                        codigo > 1
                    ORDER BY
                        descripcion;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = $result;
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return $error;
        }
    }
    
    public function getRolesMonitoreos(){
        try {
            $sql = "SELECT 
                        *
                    FROM 
                        roles_destinos 
                    where 
                        rol_id = " . $_SESSION["rol_selected"] . " ;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = $result;
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return $error;
        }
    }

    public function cambiar_estadoRolMonitoreo($codigo, $estado, $rol){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO roles_destinos (destino_id, rol_id, permiso) VALUES (?,?,?);');
            
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
            $stmt->bindValue(2, $rol, PDO::PARAM_INT);
            $stmt->bindValue(3, $estado, PDO::PARAM_INT);
            
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

    public function deleteArchivo_destino($codigo, $rol){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from roles_destinos where destino_id = ? and rol_id = ?');
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
            $stmt->bindValue(2, $rol, PDO::PARAM_INT);
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
}	
?>