<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

//include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";
include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion_utils.php";

class MenusModel {
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

    public static function singleton_menus() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountMenus(){
        try {
            $sql = "SELECT count(*) as cuenta FROM menus WHERE id > 0;";
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
                        menus m
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
    
    public function deleteMenus($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from menus where codigo = ?');
            $stmt->bindValue(1, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = 0;
                return 0;
            }  else {
                $this->conn->rollBack();
                $_SESSION["JSON"]["status"] = "Error";
                $_SESSION["JSON"]["message"] = $stmt->errorInfo();
                return 1;
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return -1;
        }
    }
    
    public function addMenus($descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden){
        $hoy = date("Y-m-d H:i:s");
        $ahora = date("Y-m-d H:i:s");
        $previo = $this->getMenus($orden)[0];
        $prev_orden = $previo["orden"];
        if ($nivel == 1){
            
        }
        if ($nivel == 2){
            $niveles *= -1;
        }
        if ($nivel == 3){
            $niveles *= -1;
            $subniveles *= -1;
        }
        try {
            $this->conn->beginTransaction();            
            if ($prev_orden > 0){
                $stmt = $this->conn->prepare('update menus set orden = orden + 2 where orden > ?;');
                $stmt->bindValue(1, $prev_orden, PDO::PARAM_INT);
                $stmt->execute();
                $prev_orden++;
            } else {
                $prev_orden = 1;
            }
            
            $stmt = $this->conn->prepare('INSERT INTO menus (descripcion, nombre, destino, nivel, icono, subnivel, orden, visible) VALUES (?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $destino, PDO::PARAM_STR);
            $stmt->bindValue(2, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(3, $destino, PDO::PARAM_STR);
            $stmt->bindValue(4, $niveles, PDO::PARAM_INT);
            $stmt->bindValue(5, $icono, PDO::PARAM_STR);
            $stmt->bindValue(6, $subniveles, PDO::PARAM_INT);
            $stmt->bindValue(7, $prev_orden, PDO::PARAM_INT);
            $stmt->bindValue(8, $visible, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                $sql = "SELECT 
                            max(codigo) as maximo
                        FROM 
                            menus ";
                $query = $this->conn->prepare($sql);
                $query->execute();
                if ($query->rowCount() > 0) {
                    $result = $query->fetchAll(PDO::FETCH_ASSOC);
                    $code = $result[0]["maximo"];
                    if (strpos($destino, "#") !== false){
                        if ($nivel == 1){
                            $stmt = $this->conn->prepare('UPDATE menus set '
                                                            . 'nivel = ? '
                                                            . ' where codigo = ?');            
                            $stmt->bindValue(1, $code, PDO::PARAM_INT);
                            $stmt->bindValue(2, $code, PDO::PARAM_INT);                 
                            if($stmt->execute()){
                                $this->conn->commit();
                            }
                        }
                        if ($nivel == 2){
                            $stmt = $this->conn->prepare('UPDATE menus set '
                                                            . 'subnivel = ? '
                                                            . ' where codigo = ?');            
                            $stmt->bindValue(1, $code, PDO::PARAM_INT);
                            $stmt->bindValue(2, $code, PDO::PARAM_INT);                 
                            if($stmt->execute()){
                                $this->conn->commit();
                            }
                        }
                    }
                }
                                
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = 0;
                return 0;
            }  else {
                $this->conn->rollBack();
                $_SESSION["JSON"]["status"] = "Error";
                $_SESSION["JSON"]["message"] = $stmt->errorInfo();
                return var_dump($stmt->errorInfo());
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return -1;
        }
    }
    
    public function updateMenus($codigo, $fecha_modif, $descripcion, $nivel, $niveles, $subniveles, $visible, $destino, $icono, $orden){
        $hoy = date("Y-m-d H:i:s");
        $ahora = date("Y-m-d H:i:s");
        $previo = $this->getMenus($orden)[0];
        $prev_orden = $previo["orden"];
        if ($nivel == 1){
            
        }
        if ($nivel == 2){
            $niveles *= -1;
        }
        if ($nivel == 3){
            $niveles *= -1;
            $subniveles *= -1;
        }
        try {
            $this->conn->beginTransaction();
             if ($prev_orden > 0){
                $stmt = $this->conn->prepare('update menus set orden = orden + 2 where orden > ?;');
                $stmt->bindValue(1, $prev_orden, PDO::PARAM_INT);
                $stmt->execute();
                $prev_orden++;
            } else {
                $prev_orden = 1;
            }
            $stmt = $this->conn->prepare('UPDATE menus set '
                                            . 'descripcion = ? , '
                                            . 'nombre = ? , '
                                            . 'destino = ? , '
                                            . 'nivel = ? , '
                                            . 'icono = ? , '
                                            . 'subnivel = ? , '
                                            . 'orden = ? , '
                                            . 'visible = ? '
                                            . ' where codigo = ?');            
            $stmt->bindValue(1, $destino, PDO::PARAM_STR);
            $stmt->bindValue(2, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(3, $destino, PDO::PARAM_STR);
            $stmt->bindValue(4, $niveles, PDO::PARAM_INT);
            $stmt->bindValue(5, $icono, PDO::PARAM_STR);
            $stmt->bindValue(6, $subniveles, PDO::PARAM_INT);
            $stmt->bindValue(7, $prev_orden, PDO::PARAM_INT);
            $stmt->bindValue(8, $visible, PDO::PARAM_INT);  
            $stmt->bindValue(9, $codigo, PDO::PARAM_INT);                 
            if($stmt->execute()){
                $this->conn->commit();
                
                $code = $codigo;
                if (strpos($destino, "#") !== false){
                    if ($nivel == 1){
                        $stmt = $this->conn->prepare('UPDATE menus set '
                                                        . 'nivel = ? '
                                                        . ' where codigo = ?');            
                        $stmt->bindValue(1, $code, PDO::PARAM_INT);
                        $stmt->bindValue(2, $code, PDO::PARAM_INT);                 
                        if($stmt->execute()){
                            $this->conn->commit();
                        }
                    }
                    if ($nivel == 2){
                        $stmt = $this->conn->prepare('UPDATE menus set '
                                                        . 'subnivel = ? '
                                                        . ' where codigo = ?');            
                        $stmt->bindValue(1, $code, PDO::PARAM_INT);
                        $stmt->bindValue(2, $code, PDO::PARAM_INT);                 
                        if($stmt->execute()){
                            $this->conn->commit();
                        }
                    }
                }
                
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = 0;
                return 0;
            }  else {
                $this->conn->rollBack();
                $_SESSION["JSON"]["status"] = "Error";
                $_SESSION["JSON"]["message"] = $stmt->errorInfo();
                return var_dump($stmt->errorInfo());
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return -1;
        }
    }
    
    public function getMenus($codigo){
        try {
            $sql = "SELECT 
                        menus.*
                    FROM 
                        menus
                    WHERE
                        menus.codigo = " . $codigo . ";";
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
    
    public function getMenusAll(){
        try {
            $sql = "SELECT 
                        menus.*
                    FROM 
                        menus
                    ORDER BY
                        FIELD(nivel, 0) DESC,
                        menus.orden asc;";
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
      
}	
?>