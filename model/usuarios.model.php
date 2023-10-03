<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class UsuariosModel {
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

    public static function singleton_usuarios() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountUsuarios(){
        try {
            $sql = "SELECT count(*) FROM usuarios;";
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
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        try {
            $desde = ($pagina - 1) * $registros;
            $sql = "SELECT * FROM usuarios WHERE fecha_baja is null and usuario like '%" . $busqueda . "%' ORDER BY " . $orderby . " " . $sentido;
            if (intval($registros) > 0){
                $sql_limit = $sql . " limit " . $desde . "," . $registros . ";";
            } else {
                $sql_limit = $sql ;
            }
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
            $stmt = $this->conn->prepare('update usuarios set fecha_baja = now() where codigo = ?');
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
    
    public function addUsuario($id_usu, $usuario, $password, $cliente, $cargo, $nombre, $apellido, $mail){
        if ($cargo == "Administrador"){
            $cliente = null;
        }
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO usuarios (codigo, usuario, password, surname, id_rol, name, nombre, id_cliente, mail, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $id_usu, PDO::PARAM_INT);
            $stmt->bindValue(2, $usuario, PDO::PARAM_STR);
            $stmt->bindValue(3, $password, PDO::PARAM_STR);
            $stmt->bindValue(4, $apellido, PDO::PARAM_STR);
            $stmt->bindValue(5, $cargo, PDO::PARAM_INT);
            $stmt->bindValue(6, $nombre, PDO::PARAM_STR);
            $stmt->bindValue(7, $nombre . " " . $apellido, PDO::PARAM_STR);
            $stmt->bindValue(8, $_SESSION["id_cliente"], PDO::PARAM_INT);
            $stmt->bindValue(9, $mail, PDO::PARAM_STR);
            $stmt->bindValue(10, $_SESSION["user_id"], PDO::PARAM_INT);
            $stmt->bindValue(11, $hoy, PDO::PARAM_STR);
            
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
    
    public function updateUsuario($codigo, $usuario, $password, $cliente, $cargo, $nombre, $apellido, $mail){
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
                                            . 'nombre = ? , '
                                            . 'mail = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');
            
            $stmt->bindValue(1, $usuario, PDO::PARAM_STR);
            $stmt->bindValue(2, $password, PDO::PARAM_STR);
            $stmt->bindValue(3, $apellido, PDO::PARAM_STR);
            $stmt->bindValue(4, $cargo, PDO::PARAM_INT);
            $stmt->bindValue(5, $nombre, PDO::PARAM_STR);
            $stmt->bindValue(6, $nombre . " " . $apellido, PDO::PARAM_STR);
            $stmt->bindValue(7, $mail, PDO::PARAM_STR);
            $stmt->bindValue(8, $_SESSION["user_id"], PDO::PARAM_STR);
            $stmt->bindValue(9, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(10, $codigo, PDO::PARAM_INT);
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
    
    public function getUsuario($codigo){
        try {
            $sql = "SELECT * FROM usuarios WHERE codigo = " . $codigo . ";";
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
    
    public function getDestinos(){
        try {
            $sql = "SELECT * FROM destinos order by descripcion;";
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
    
    public function getUsuarios(){
        try {
            $sql = "SELECT * FROM usuarios order by usuario;";
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
    
    public function afectar($seleccionados, $cod_usuario){
        $hoy = date("Y-m-d H:i:s");
        $ahora = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from usuarios_destinos where usuario_id = ' . intval($cod_usuario) . ' ');          
            $stmt->execute();
            $fase = 2;
            foreach ($seleccionados as $s){            
                $stmt = $this->conn->prepare('INSERT INTO usuarios_destinos (usuario_id, destino_id, usuario_m, fecha_m) VALUES (?,?,?,?);');
                $stmt->bindValue(1, $cod_usuario, PDO::PARAM_INT);
                $stmt->bindValue(2, $s, PDO::PARAM_INT);
                $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
                $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
                if($stmt->execute()){
                    
                }  else {
                    $this->conn->rollBack();
                    return var_dump($stmt->errorInfo());
                }
            }
            $this->conn->commit();
            return 0;
        } catch(PDOException $e) {
            $this->conn->rollBack();
            return -1;
        }
    }
    
    public function deleteUsuarioMenu($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from menus_usuarios where cod_usuario = ?');
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

    public function addUsuarioMenu($id_usu, $id_menu){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO menus_usuarios (cod_menu, cod_usuario, view, edit, eliminar, new, access, permiso, usuario_m, fecha_m) VALUES (?, ?, 1, 1, 1, 1, 1, 1, ?, ?);');
            $stmt->bindValue(1, $id_menu, PDO::PARAM_INT);
            $stmt->bindValue(2, $id_usu, PDO::PARAM_INT);
            $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            
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
    
    public function getLastUsuario(){
        try {
            $sql = "SELECT * FROM usuarios order by codigo desc;";
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