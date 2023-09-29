<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class IndexModel {
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

    public static function singleton_index() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
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
                        mu.access as access,
                        r.administrador as rol_administrador,
                        r.estado_ot as rol_estado_ot,
                        r.finalizar_ot as rol_finalizar_ot,
                        r.editar_ot as rol_editar_ot,
                        r.editar_files_otp as rol_editar_files_otp,
                        r.delete_otp as rol_delete_otp,
                        r.view_all_files as rol_view_all_files
                    from 
                        usuarios u, 
                        roles r,
                        menus m, 
                        menus_usuarios mu 
                    where 
                        u.id_rol = r.codigo and
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
                $_SESSION["sistemas"]           = $result[0]["sistemas"];

                $_SESSION["rol_administrador"]      = $result[0]["rol_administrador"];
                $_SESSION["rol_estado_ot"]          = $result[0]["rol_estado_ot"];
                $_SESSION["rol_finalizar_ot"]       = $result[0]["rol_finalizar_ot"];
                $_SESSION["rol_editar_ot"]          = $result[0]["rol_editar_ot"];
                $_SESSION["rol_editar_files_otp"]   = $result[0]["rol_editar_files_otp"];
                $_SESSION["rol_delete_otp"]         = $result[0]["rol_delete_otp"];
                $_SESSION["rol_view_all_files"]     = $result[0]["rol_view_all_files"];

                $_SESSION["cliente"]            = "Giuliani";
                $_SESSION["ultimo_cliente"]     = "Giuliani";
                $_SESSION["permisos"]           = array();
                $_SESSION["sucursales"]         = "";
                if ($_SESSION["sistemas"] == 1){
                    $_SESSION["last_cliente"]       = $_SESSION["id_cliente"];
                    $_SESSION["selected_cliente"]   = $_SESSION["id_cliente"];                    
                } else {
                    $_SESSION["id_cliente"]         = $result[0]["id_cliente"];
                    $_SESSION["last_cliente"]       = $result[0]["id_cliente"];
                    $_SESSION["selected_cliente"]   = $result[0]["id_cliente"];
                }
                foreach ($result as $res){
                    $_SESSION["permisos"][$res["menu_desc"]]["view"]        = $res["view"];
                    $_SESSION["permisos"][$res["menu_desc"]]["edit"]        = $res["edit"];
                    $_SESSION["permisos"][$res["menu_desc"]]["eliminar"]    = $res["eliminar"];
                    $_SESSION["permisos"][$res["menu_desc"]]["new"]         = $res["new"];
                    $_SESSION["permisos"][$res["menu_desc"]]["access"]      = $res["access"];
                }  
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
        
    }
    
    public function logueoEncrypt($user,$pass){
        
        try {
            $sql = "select 
                        u.*, 
                        m.descripcion as menu_desc, 
                        mu.view as view, 
                        mu.edit as edit, 
                        mu.eliminar as eliminar, 
                        mu.new as new, 
                        mu.access as access,
                        r.administrador as rol_administrador,
                        r.estado_ot as rol_estado_ot,
                        r.finalizar_ot as rol_finalizar_ot,
                        r.editar_ot as rol_editar_ot,
                        r.editar_files_otp as rol_editar_files_otp,
                        r.delete_otp as rol_delete_otp,
                        r.view_all_files as rol_view_all_files
                    from 
                        usuarios u, 
                        roles r,
                        menus m, 
                        menus_usuarios mu 
                    where 
                        u.id_rol = r.codigo and
                        mu.cod_usuario = u.codigo and 
                        mu.cod_menu = m.codigo and 
                        u.usuario = '" . $user . "' and 
                        u.password = '" . $pass . "';";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["usuario"]            = $user;
                $_SESSION["password"]           = $pass;
                $_SESSION["user_id"]            = $result[0]["codigo"];
                $_SESSION["cargo"]              = $result[0]["cargo"];
                $_SESSION["nombre"]             = $result[0]["nombre"];
                $_SESSION["apellido"]           = $result[0]["apellido"];
                $_SESSION["sistemas"]           = $result[0]["sistemas"];                

                $_SESSION["rol_administrador"]      = $result[0]["rol_administrador"];
                $_SESSION["rol_estado_ot"]          = $result[0]["rol_estado_ot"];
                $_SESSION["rol_finalizar_ot"]       = $result[0]["rol_finalizar_ot"];
                $_SESSION["rol_editar_ot"]          = $result[0]["rol_editar_ot"];
                $_SESSION["rol_editar_files_otp"]   = $result[0]["rol_editar_files_otp"];
                $_SESSION["rol_delete_otp"]         = $result[0]["rol_delete_otp"];
                $_SESSION["rol_view_all_files"]     = $result[0]["rol_view_all_files"];

                $_SESSION["id_cliente"]         = 1;
                $_SESSION["cliente"]            = "Giuliani";
                $_SESSION["last_cliente"]       = 1;
                $_SESSION["ultimo_cliente"]     = "Giuliani";
                $_SESSION["selected_cliente"]   = 1;
                $_SESSION["permisos"]           = array();
                $_SESSION["sucursales"]         = "";
                foreach ($result as $res){
                    $_SESSION["permisos"][$res["menu_desc"]]["view"]        = $res["view"];
                    $_SESSION["permisos"][$res["menu_desc"]]["edit"]        = $res["edit"];
                    $_SESSION["permisos"][$res["menu_desc"]]["eliminar"]    = $res["eliminar"];
                    $_SESSION["permisos"][$res["menu_desc"]]["new"]         = $res["new"];
                    $_SESSION["permisos"][$res["menu_desc"]]["access"]      = $res["access"];
                }   
                return 0;
            } else {
                return 1;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    
    public function getMenuDestinos($rol){
        try {
            $sql = "select 
                        m.*
                    from 
                        roles_destinos m
                    where
                        m.rol_id = " . intval($rol) . " 
                    order by
                        m.destino_id asc;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    

    public function getMenuRoles($rol){
        try {
            $sql = "select 
                        m.*
                    from 
                        roles_menus m
                    where
                        m.rol_id = " . intval($rol) . " 
                    order by
                        m.menu_id asc;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
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
    
    public function updateBell(){
        try {
            $sql = "select 
                        count(*) as cuenta
                    from 
                        orders_pedido p, 
                        orders_pedido_estado e,
                        orders_sucursal_usuario us
                    where 
                        p.sucursal_id = us.sucursal_id and
                        e.id = p.pedido_estado_id and
                        e.pendiente = 1 ";
            $sql .= " and us.usuario_id = " . $_SESSION["user_local_id"] . " ";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);    
                return $result[0]["cuenta"];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
        
    }
    
    public function getProductos(){
        try {
            $sql = "SELECT 
                        productos.*,
                        (select ci.descripcion from conf_inicial ci where ci.cod_producto = productos.codigo) as inicial,
                        (select sum(det.cant_prod) from semanas_detalles det where det.cod_producto = productos.codigo) as producido,
                        (select sum(det.cant_vend) from semanas_detalles det where det.cod_producto = productos.codigo) as vendido, 
                        DATE_FORMAT(productos.fecha_m, '%Y-%m-%d %H:%i:%s') as fecha_modif
                    FROM 
                        productos
                    WHERE
                        codigo > 0 ";
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
    
    public function getAjustes($cod_producto){
        try {
            $sql = "SELECT 
                        a.*,
                        DATE_FORMAT(a.fecha_m, '%Y-%m-%d %H:%i:%s') as fecha_modif
                    FROM 
                        ajustes a
                    WHERE
                        a.cod_producto = " . intval($cod_producto) . " ";
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
    
    public function getVentas($cod_producto){
        try {
            $sql = "SELECT 
                        v.*,
                        p.descripcion as persona,
                        DATE_FORMAT(v.fecha_m, '%Y-%m-%d %H:%i:%s') as fecha_modif
                    FROM 
                        ventas v,
                        personas p
                    WHERE
                        v.cod_persona = p.codigo and
                        v.cod_producto = " . intval($cod_producto) . " ";
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
    
    public function getDisponibleCheques(){
        try {
            $sql = "SELECT sum(importe) as disponible FROM cheques where year(fecha_salida) <= 2000 or fecha_salida is null;";
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
    
    public function getDisponibleBancos(){
        try {
            $sql = "SELECT sum(mb.importe * c.impacto) as disponible FROM movimientos_banco mb, conceptos c WHERE c.codigo = mb.cod_concepto;";
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
    
    public function getDisponibleCaja(){
        try {
            $sql = "SELECT sum(mb.importe * c.impacto) as disponible FROM movimientos_caja mb, conceptos c WHERE c.codigo = mb.cod_concepto;";
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
    
    public function getCtasCtesClientes(){
        try {
            $sql = "SELECT
                        p.descripcion as persona,
                        v.codigo as codigo,
                        concat('Vta.: ', pr.descripcion) as descripcion, 
                        -1 as impacto, 
                        v.subtotal as importe, 
                        v.fecha 
                    FROM 
                        ventas v,
                        productos pr,
                        personas p
                    WHERE 
                        v.cod_producto = pr.codigo and
                        v.cod_persona = p.codigo 
                union 
                   (SELECT 
                        p.descripcion as persona,
                        c.codigo as codigo,
                        concat('Cobro') as descripcion, 
                        1 as impacto, 
                        c.importe, 
                        c.fecha 
                    FROM 
                        cobros c,
                        personas p
                    WHERE 
                        c.cod_persona = p.codigo
                    )  
                order by fecha";
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
    
    public function getCtasCtesProv(){
        try {
            $sql = "SELECT
                        p.descripcion as persona,
                        v.codigo as codigo,
                        concat('Compra: ', pr.descripcion) as descripcion, 
                        -1 as impacto, 
                        v.subtotal as importe, 
                        v.fecha 
                    FROM 
                        compras v,
                        insumos pr,
                        personas p
                    WHERE 
                        v.cod_producto = pr.codigo and
                        v.cod_persona = p.codigo 
                union 
                   (SELECT 
                        p.descripcion as persona,
                        c.codigo as codigo,
                        concat('Pago') as descripcion, 
                        1 as impacto, 
                        c.importe, 
                        c.fecha 
                    FROM 
                        pagos c,
                        personas p
                    WHERE 
                        c.cod_persona = p.codigo
                    )  
                order by fecha";
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
    
    public function getArchivo($archivo_id){
        try {
            $sql = "SELECT * FROM archivos where codigo = " . $archivo_id . ";";
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
    
    public function getTextoInicio(){
        try {
            $sql = "SELECT * FROM utils where descripcion = 'texto_inicio';";
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
    
    public function updateFrase($textarea){
        try {
            $sql = "update utils set valor = '" . $textarea . "' where descripcion = 'texto_inicio';";
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