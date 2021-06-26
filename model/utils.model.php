<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion_utils.php";

class UtilsModel {
    private static $instancia;
    
    private $conn;

    public function __construct() {
        try {                
            $this->conn = ConexionUtils::singleton_conexion();
        } catch ( Exception $e ) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_utils() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function newFunction($funcion, $menu){
        try {
            $sql = "INSERT INTO conf_functions (descripcion, cod_controller)
                    SELECT * FROM (SELECT '" . $funcion . "', (SELECT codigo FROM conf_controllers WHERE descripcion = '" . $menu . "')) AS tmp
                    WHERE NOT EXISTS (
                        SELECT descripcion, cod_controller FROM conf_functions WHERE descripcion = '" . $funcion . "' and cod_controller = (SELECT codigo FROM conf_controllers WHERE descripcion = '" . $menu . "')
                    )";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result[0];
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    
    public function newController($controller, $menu){
        try {
            $sql = "INSERT INTO conf_controllers (descripcion, menu)
                    SELECT * FROM (SELECT '" . $controller . "', '" . $menu . "') AS tmp
                    WHERE NOT EXISTS (
                        SELECT descripcion, menu FROM conf_controllers WHERE descripcion = '" . $controller . "' and menu = '" . $menu . "'
                    )";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result[0];
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    
    public function newMenu($menu){
        try {
            $sql = "INSERT INTO menus (descripcion)
                    SELECT * FROM (SELECT '" . $menu . "') AS tmp
                    WHERE NOT EXISTS (
                        SELECT descripcion FROM menus WHERE descripcion = '" . $menu . "'
                    )";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result[0];
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    
    public function logueoEncrypt($user,$pass, $cliente){
        try {
            $sql = "select 
                        u.*
                    from 
                        usuarios u
                    where ";
            $sql .= "  u.usuario = '" . $user . "' and 
                        u.password = '" . $pass . "';";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["usuario"]  = $user;
                $_SESSION["password"] = $pass;
                $_SESSION["user_id"]            = $result[0]["codigo"];
                $_SESSION["cargo"]              = $result[0]["cargo"];
                $_SESSION["rol"]                = $result[0]["id_rol"];
                $_SESSION["nombre"]             = $result[0]["nombre"];
                $_SESSION["apellido"]           = $result[0]["apellido"];
                $_SESSION["id_cliente"]         = $result[0]["id_cliente"];
                $_SESSION["sistemas"]           = $result[0]["sistemas"];
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
        
    }
    
    public function getUsuarios(){
        try {
            $sql = "SELECT 
                        *,
                        usuario as descripcion
                    FROM 
                        usuarios;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    
    public function getSistemas(){
        try {
            $sql = "SELECT 
                        *
                    FROM 
                        usuarios
                    WHERE
                        sistemas = 1;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    
    public function getRoles($id_cliente){
        try {
            $sql = "SELECT 
                        *
                    FROM 
                        roles";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
        
    
    public function deleteUsuario($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from usuarios where codigo = ?');
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
    
    public function addUsuario($usuario, $password, $cliente, $cargo, $nombre, $apellido){
        if ($cargo == "Administrador"){
            $cliente = null;
        }
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO usuarios (usuario, password, surname, id_rol, name, id_cliente, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $usuario, PDO::PARAM_STR);
            $stmt->bindValue(2, $password, PDO::PARAM_STR);
            $stmt->bindValue(3, $apellido, PDO::PARAM_STR);
            $stmt->bindValue(4, $cargo, PDO::PARAM_INT);
            $stmt->bindValue(5, $nombre, PDO::PARAM_STR);
            $stmt->bindValue(6, $_SESSION["id_cliente"], PDO::PARAM_INT);
            $stmt->bindValue(7, $_SESSION["user_id"], PDO::PARAM_INT);
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
    
    public function updateUsuario($codigo, $usuario, $password, $cliente, $cargo, $nombre, $apellido){
        if ($cargo == "Administrador"){
            $cliente = null;
        }
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE usuarios set '
                                            . 'usuario = ? , '
                                            . 'password = ? , '
                                            . 'surname = ? , '
                                            . 'id_rol = ? , '
                                            . 'name = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where id = ?');
            
            $stmt->bindValue(1, $usuario, PDO::PARAM_STR);
            $stmt->bindValue(2, $password, PDO::PARAM_STR);
            $stmt->bindValue(3, $apellido, PDO::PARAM_STR);
            $stmt->bindValue(4, $cargo, PDO::PARAM_INT);
            $stmt->bindValue(5, $nombre, PDO::PARAM_STR);
            $stmt->bindValue(6, $_SESSION["user_id"], PDO::PARAM_STR);
            $stmt->bindValue(7, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(8, $codigo, PDO::PARAM_INT);
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
    
    public function logueo($user,$pass){
        try {
            $sql = "select 
                        u.*, 
                        m.descripcion as menu_desc, 
                        mu.view as view, 
                        mu.edit as edit, 
                        mu.eliminar as eliminar, 
                        mu.new as new, 
                        mu.access as access 
                    from 
                        usuarios u, 
                        menus m, 
                        menus_usuarios mu 
                    where 
                        mu.cod_usuario = u.codigo and 
                        mu.cod_menu = m.codigo and 
                        u.usuario = '" . $user . "' and 
                        u.password = '" . md5($pass) . "';";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["usuario"]            = $user;
                $_SESSION["password"]           = $pass;
                $_SESSION["user_id"]            = $result[0]["codigo"];
                $_SESSION["cargo"]              = $result[0]["cargo"];
                $_SESSION["rol"]                = $result[0]["id_rol"];
                $_SESSION["nombre"]             = $result[0]["nombre"];
                $_SESSION["apellido"]           = $result[0]["apellido"];
                //$_SESSION["id_cliente"]         = $result[0]["id_cliente"];
                $_SESSION["sistemas"]           = $result[0]["sistemas"];
                $_SESSION["cliente"]            = "Giuliani";
                //$_SESSION["last_cliente"]       = $result[0]["id_cliente"];
                $_SESSION["ultimo_cliente"]     = "Giuliani";
                //$_SESSION["selected_cliente"]   = $result[0]["id_cliente"];
                $_SESSION["permisos"]           = array();
                $_SESSION["sucursales"]         = "";
                foreach ($result as $res){
                    $_SESSION["permisos"][$res["menu_desc"]]["view"]        = $res["view"];
                    $_SESSION["permisos"][$res["menu_desc"]]["edit"]        = $res["edit"];
                    $_SESSION["permisos"][$res["menu_desc"]]["eliminar"]    = $res["eliminar"];
                    $_SESSION["permisos"][$res["menu_desc"]]["new"]         = $res["new"];
                    $_SESSION["permisos"][$res["menu_desc"]]["access"]      = $res["access"];
                }    
                $destinos = $this->getUsuariosDestinos($result[0]["codigo"]);
                $_SESSION["destinos"]           = ",";
                foreach ($destinos as $res){
                    $_SESSION["destinos"] .= $res["destino_id"].",";
                }
                /*$_SESSION["destinos"] = substr($_SESSION["destinos"],0,-1);
                if (count($destinos) > 0){
                    $_SESSION["destinos"] .= $res["destino_id"].",";
                }*/
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
        
    }
    
    public function getMenuUser($user){
        try {
            $sql = "select 
                        mu.*
                    from 
                        menus m,
                        menus_usuarios mu
                    where
                        m.codigo = mu.cod_menu and
                        mu.cod_usuario = " . intval($user) . " 
                    order by
                        m.orden asc;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    
    
    public function getMenu(){
        try {
            $sql = "select 
                        m.*,
                        m.descripcion as menu_desc, 
                        mu.view as view, 
                        mu.edit as edit, 
                        mu.eliminar as eliminar, 
                        mu.new as new, 
                        mu.access as access 
                    from 
                        menus m, 
                        menus_usuarios mu 
                    where 
                        m.visible = 1 and
                        mu.cod_menu = m.codigo and 
                        mu.cod_usuario = " . $_SESSION["user_id"] . "
                    order by
                        m.orden asc;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    
    
    public function getUsuariosDestinos($cod_usuario){
        try {
            $sql = "SELECT * FROM usuarios_destinos where usuario_id = " . intval($cod_usuario) . ";";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
}	
?>