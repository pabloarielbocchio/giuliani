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
            $sql = "SELECT count(*) FROM productos_nivel_a;";
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
    
    public function buscarArchivos($n1, $n2, $n3, $n4, $opc){
        try {
            $conditions = "";
            if ($n1 >= 0){
                $conditions .= " and cod_prod_na = " . intval($n1);
            }
            if ($n2 >= 0){
                $conditions .= " and cod_prod_nb = " . intval($n2);
            }
            if ($n3 >= 0){
                $conditions .= " and cod_prod_nc = " . intval($n3);
            }
            if ($n4 >= 0){
                $conditions .= " and cod_prod_nd = " . intval($n4);
            }
            $tabla = "archivos";
            $sql = "SELECT *, descripcion as nombre FROM " . $tabla . " WHERE 1 = 1 " . $conditions . " and descripcion like '%" . $busqueda . "%' order by descripcion";
            
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();

                foreach($result as $k => $aux){
                    $prefijo = "";
                    if ($aux["codigo"] < 10000){
                        $prefijo .= "0";
                    }
                    if ($aux["codigo"] < 1000){
                        $prefijo .= "0";
                    }
                    if ($aux["codigo"] < 100){
                        $prefijo .= "0";
                    }
                    if ($aux["codigo"] < 10){
                        $prefijo .= "0";
                    }
                    $prefijo .= $aux["codigo"] . " - ";
                    $result[$k]["prefijo"] = $prefijo;
                }

                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
        
    }
    
    public function buscarArchivosOtd($n1, $n2, $n3, $n4, $opc){
        try {
            $conditions = "";
            $conditions .= " and cod_ot_detalle_id = " . intval($opc);
            $tabla = "archivos";
            $sql = "SELECT *, descripcion as nombre FROM " . $tabla . " WHERE 1 = 1 " . $conditions . " and descripcion like '%" . $busqueda . "%' order by descripcion ";
            
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
    
    public function buscarArchivosOt($n1, $n2, $n3, $n4, $opc){
        try {
            $conditions = "";
            $conditions .= " and cod_ot = " . intval($opc);
            $tabla = "archivos";
            $sql = "SELECT *, descripcion as nombre FROM " . $tabla . " WHERE 1 = 1 " . $conditions . " and descripcion like '%" . $busqueda . "%' order by descripcion ";
            
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
    
    public function buscarArchivosP($opc){
        try {
            $conditions = "";
            $conditions .= " and cod_prod_personalizado_id = " . intval($opc);
            $tabla = "archivos";
            $sql = "SELECT *, descripcion as nombre FROM " . $tabla . " WHERE 1 = 1 " . $conditions . " order by descripcion ";            
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
    
    public function getDetalles(){
        try {
            $sql = "SELECT otp.*, otd.seccion, otd.sector, otd.item_vendido, otd.observaciones, otd.orden_trabajo_id, (select cliente from orden_trabajos where codigo = otd.orden_trabajo_id) as cliente FROM orden_trabajos_detalles otd, orden_trabajos_produccion otp WHERE otp.ot_detalle_id = otd.codigo ";            
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
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4){
        try {
            $desde = ($pagina - 1) * $registros;
            if ($n4 == 0){
                $conditions = " and cod_prod_nc = " . intval($n3);
                $tabla = "productos_nivel_d";
            } else {
                $conditions = " and cod_prod_nd = " . intval($n4);
                $tabla = "productos_nivel_e";
            }
            if ($n3 == 0){
                $conditions = " and cod_prod_nb = " . intval($n2);
                $tabla = "productos_nivel_c";
            }
            if ($n2 == 0){
                $conditions = " and cod_prod_na = " . intval($n1);
                $tabla = "productos_nivel_b";
            }
            if ($n1 == 0){
                $conditions = "";
                $tabla = "productos_nivel_a";
            }
            $sql = "SELECT * FROM " . $tabla . " WHERE 1 = 1 " . $conditions . " and descripcion like '%" . $busqueda . "%' ORDER BY " . $orderby . " " . $sentido;
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
    
    public function getRegistrosFiltroOpciones($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4){
        try {
            $desde = ($pagina - 1) * $registros;
            $conditions = " and cod_prod_nd = " . intval($n4);
            $tabla = "productos_nivel_e";
            $sql = "SELECT * FROM " . $tabla . " WHERE 1 = 1 " . $conditions . " and descripcion like '%" . $busqueda . "%' ORDER BY " . $orderby . " " . $sentido;
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
    
    public function deleteProducto($codigo, $n1, $n2, $n3, $n4){
        try {
            $conditions = "";
            $this->conn->beginTransaction();
            if ($n4 == 0){
                $tabla = "productos_nivel_d";
                $conditions = " AND ( codigo NOT IN (SELECT ad.cod_prod_nd FROM productos_estandar ad) and codigo NOT IN (SELECT ad.cod_prod_nd FROM productos_nivel_e ad) )";
            } else {
                $tabla = "productos_nivel_e";
                $conditions = "  AND ( codigo NOT IN (SELECT ad.cod_prod_ne FROM productos_nivel_f ad) )";
            }
            if ($n3 == 0){
                $tabla = "productos_nivel_c";
                $conditions = " AND ( codigo NOT IN (SELECT ad.cod_prod_nc FROM productos_nivel_d ad) )";
            }
            if ($n2 == 0){
                $tabla = "productos_nivel_b";
                $conditions = " AND ( codigo NOT IN (SELECT ad.cod_prod_nb FROM productos_nivel_c ad) )";
            }
            if ($n1 == 0){
                $tabla = "productos_nivel_a";
                $conditions = " AND ( codigo NOT IN (SELECT ad.cod_prod_na FROM productos_nivel_b ad) )";
            }
            $stmt = $this->conn->prepare('DELETE from ' . $tabla . ' where codigo = ? ' . $conditions);
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
    
    public function deleteProductoAlt($codigo){
        try {
            $this->conn->beginTransaction();
            $tabla = "productos_nivel_f";
            $stmt = $this->conn->prepare('DELETE from ' . $tabla . ' where codigo = ? AND ( codigo NOT IN (SELECT ad.prod_f_id FROM productos_configuraciones ad) )');
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
    
    public function addProducto($descripcion, $n1, $n2, $n3, $n4){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            
            if ($n4 == 0){
                $stmt = $this->conn->prepare('INSERT INTO productos_nivel_d (descripcion, cod_prod_nc, usuario_m, fecha_m) VALUES (?,?,?,?);');
                $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
                $stmt->bindValue(2, $n3, PDO::PARAM_INT);
                $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
                $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            } else {
                $stmt = $this->conn->prepare('INSERT INTO productos_nivel_e (descripcion, cod_prod_nd, usuario_m, fecha_m) VALUES (?,?,?,?);');
                $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
                $stmt->bindValue(2, $n4, PDO::PARAM_INT);
                $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
                $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            }
            if ($n3 == 0){
                $stmt = $this->conn->prepare('INSERT INTO productos_nivel_c (descripcion, cod_prod_nb, usuario_m, fecha_m) VALUES (?,?,?,?);');
                $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
                $stmt->bindValue(2, $n2, PDO::PARAM_INT);
                $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
                $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            }
            if ($n2 == 0){
                $stmt = $this->conn->prepare('INSERT INTO productos_nivel_b (descripcion, cod_prod_na, usuario_m, fecha_m) VALUES (?,?,?,?);');
                $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
                $stmt->bindValue(2, $n1, PDO::PARAM_INT);
                $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
                $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            }
            if ($n1 == 0){
                $stmt = $this->conn->prepare('INSERT INTO productos_nivel_a (descripcion, usuario_m, fecha_m) VALUES (?,?,?);');
                $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
                $stmt->bindValue(2, $_SESSION["usuario"], PDO::PARAM_STR);
                $stmt->bindValue(3, $hoy, PDO::PARAM_STR);
            }
            
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
    
    
    public function addProductoAlt($descripcion, $opcion){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            
            $stmt = $this->conn->prepare('INSERT INTO productos_nivel_f (descripcion, cod_prod_ne, usuario_m, fecha_m) VALUES (?,?,?,?);');
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $opcion, PDO::PARAM_INT);
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
    
    public function updateProducto($codigo, $descripcion, $n1, $n2, $n3, $n4){
        $hoy = date("Y-m-d H:i:s");
        try {
            if ($n4 == 0){
                $tabla = "productos_nivel_d";
            } else {
                $tabla = "productos_nivel_e";
            }
            if ($n3 == 0){
                $tabla = "productos_nivel_c";
            }
            if ($n2 == 0){
                $tabla = "productos_nivel_b";
            }
            if ($n1 == 0){
                $tabla = "productos_nivel_a";
            }
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE ' . $tabla . ' set '
                                            . 'descripcion = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(3, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(4, $codigo, PDO::PARAM_INT);
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
    
    public function updateProductoAlt($codigo, $descripcion){
        $hoy = date("Y-m-d H:i:s");
        try {
            $tabla = "productos_nivel_f";
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE ' . $tabla . ' set '
                                            . 'descripcion = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            $stmt->bindValue(1, $descripcion, PDO::PARAM_STR);
            $stmt->bindValue(2, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(3, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(4, $codigo, PDO::PARAM_INT);
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
    
    public function getProducto($codigo, $n1, $n2, $n3, $n4){
        try {
            if ($n4 == 0){
                $tabla = "productos_nivel_d";
            } else {
                $tabla = "productos_nivel_e";
            }
            if ($n3 == 0){
                $tabla = "productos_nivel_c";
            }
            if ($n2 == 0){
                $tabla = "productos_nivel_b";
            }
            if ($n1 == 0){
                $tabla = "productos_nivel_a";
            }
            $sql = "SELECT * FROM " . $tabla . " WHERE codigo = " . $codigo . ";";
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
    
    
    public function getProductoAlt($codigo){
        try {
                $tabla = "productos_nivel_f";
            $sql = "SELECT * FROM " . $tabla . " WHERE codigo = " . $codigo . ";";
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
    
    public function getProductosX($prod_estandar_id){
        try {
            $sql = "SELECT * FROM productos_configuraciones where prod_standar_id = " . intval($prod_estandar_id) . ";";
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
    
    public function getProduccions($opc){
        try {
            $sql = "SELECT * FROM orden_trabajos_produccion where codigo = " . intval($opc) . ";";
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
    
    public function getProductosAll(){
        try {
            $sql = "
                SELECT 
                    na.codigo AS cna,
                    na.ing_estado AS ena,
                    na.descripcion AS dna,
                    nb.codigo AS cnb,
                    nb.ing_estado AS enb,
                    nb.descripcion AS dnb,
                    nc.codigo AS cnc,
                    nc.ing_estado AS enc,
                    nc.descripcion AS dnc,
                    nd.codigo AS cnd,
                    nd.ing_estado AS end,
                    nd.descripcion AS dnd,
                    ne.codigo AS cne,
                    ne.ing_estado AS ene,
                    ne.descripcion AS dne,
                    nf.codigo AS cnf,
                    nf.ing_estado AS enf,
                    nf.descripcion AS dnf
                FROM 
                    productos_nivel_a na
                        LEFT JOIN productos_nivel_b nb ON nb.cod_prod_na = na.codigo
                        LEFT JOIN productos_nivel_c nc ON nc.cod_prod_nb = nb.codigo
                        LEFT JOIN productos_nivel_d nd ON nd.cod_prod_nc = nc.codigo
                        LEFT JOIN productos_nivel_e ne ON ne.cod_prod_nd = nd.codigo
                        LEFT JOIN productos_nivel_f nf ON nf.cod_prod_ne = ne.codigo
                    WHERE 
                        1 = 1
                ORDER BY dna, cna, dnb, cnb, dnc, cnc, dnd, cnd, dne, cnd, dnf, cnf
            ";
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
            $sql = "SELECT * FROM productos_nivel_a order by CAST(descripcion AS UNSIGNED) desc, descripcion desc;";
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
            $sql = "SELECT * FROM productos_nivel_b order by CAST(descripcion AS UNSIGNED) desc, descripcion desc;";
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
            $sql = "SELECT * FROM productos_nivel_c order by CAST(descripcion AS UNSIGNED) desc, descripcion desc;";
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
            $sql = "SELECT * FROM productos_nivel_d order by CAST(descripcion AS UNSIGNED) desc, descripcion desc;";
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
            $sql = "SELECT * FROM productos_nivel_e order by CAST(descripcion AS UNSIGNED) desc, descripcion desc;";
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
            $sql = "SELECT * FROM productos_nivel_f order by CAST(descripcion AS UNSIGNED) desc, descripcion desc;";
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
    
    public function deleteArchivo($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from archivos where codigo = ? AND ( codigo NOT IN (SELECT ad.archivo_id FROM orden_trabajos_archivos ad) )');
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
    
    public function getDestinos(){
        try {
            $sql = "SELECT * FROM destinos order by orden;";
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
    
    public function getArchivosDestinos(){
        try {
            $sql = "SELECT * FROM archivo_destinos";
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
            $sql = "SELECT * FROM unidades order by descripcion desc;";
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
    
    public function getArchivosOtp($otp){
        try {
            $sql = "SELECT ota.*,
                otp.ot_detalle_id,
                otp.prod_estandar_id,
                otp.prod_personalizado_id,
                otp.standar,
                (select a.codigo from archivos a where a.codigo = ota.archivo_id) as cod_archivo,
                (select a.activo from archivos a where a.codigo = ota.archivo_id) as activo,
                (select a.fecha_m from archivos a where a.codigo = ota.archivo_id) as ultima_actualizacion,
                (select a.cod_prod_na from archivos a where a.codigo = ota.archivo_id) as cod_prod_na,
                (select a.cod_prod_nb from archivos a where a.codigo = ota.archivo_id) as cod_prod_nb,
                (select a.cod_prod_nc from archivos a where a.codigo = ota.archivo_id) as cod_prod_nc,
                (select a.cod_prod_nd from archivos a where a.codigo = ota.archivo_id) as cod_prod_nd,
                (select a.cod_prod_ne from archivos a where a.codigo = ota.archivo_id) as cod_prod_ne,
                (select a.cod_prod_nf from archivos a where a.codigo = ota.archivo_id) as cod_prod_nf,
                (select a.cod_prod_personalizado_id from archivos a where a.codigo = ota.archivo_id) as cod_prod_personalizado_id,
                (select a.cod_prod_estandar_id from archivos a where a.codigo = ota.archivo_id) as cod_prod_estandar_id,
                (select a.ruta from archivos a where a.codigo = ota.archivo_id) as ruta,
                (select a.descripcion from archivos a where a.codigo = ota.archivo_id) as archivo
             FROM orden_trabajos_archivos ota, orden_trabajos_produccion otp where ota.ot_produccion_id = otp.codigo and ota.ot_produccion_id = " . intval($otp) . " ;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                foreach($result as $k => $aux){
                    $prefijo = "";
                    if ($aux["cod_archivo"] < 10000){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 1000){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 100){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 10){
                        $prefijo .= "0";
                    }
                    $prefijo .= $aux["cod_archivo"] . " - ";
                    $result[$k]["prefijo"] = $prefijo;
                }

                return $result;
            }
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
    
    public function getArchivosOtd($otp){
        try {
            $sql = "SELECT ota.*,
                (select a.codigo from archivos a where a.codigo = ota.archivo_id) as cod_archivo,
                (select a.activo from archivos a where a.codigo = ota.archivo_id) as activo,
                (select a.fecha_m from archivos a where a.codigo = ota.archivo_id) as ultima_actualizacion,
                (select a.ruta from archivos a where a.codigo = ota.archivo_id) as ruta,
                (select a.descripcion from archivos a where a.codigo = ota.archivo_id) as archivo
             FROM orden_trabajos_archivos ota, orden_trabajos_detalles otd where ota.ot_detalle_id = otd.codigo and ota.ot_detalle_id = " . intval($otp) . " ;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                foreach($result as $k => $aux){
                    $prefijo = "";
                    if ($aux["cod_archivo"] < 10000){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 1000){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 100){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 10){
                        $prefijo .= "0";
                    }
                    $prefijo .= $aux["cod_archivo"] . " - ";
                    $result[$k]["prefijo"] = $prefijo;
                }

                return $result;
            }
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
    
    public function getArchivosOt($ot){
        try {
            $sql = "SELECT ota.*,
                (select a.codigo from archivos a where a.codigo = ota.archivo_id) as cod_archivo,
                (select a.activo from archivos a where a.codigo = ota.archivo_id) as activo,
                (select a.fecha_m from archivos a where a.codigo = ota.archivo_id) as ultima_actualizacion,
                (select a.ruta from archivos a where a.codigo = ota.archivo_id) as ruta,
                (select a.descripcion from archivos a where a.codigo = ota.archivo_id) as archivo
             FROM orden_trabajos_archivos ota, orden_trabajos otd where ota.ot_id = otd.codigo and ota.ot_id = " . intval($ot) . " ;";
            $query = $this->conn->prepare($sql);
            $query->execute();            
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                foreach($result as $k => $aux){
                    $prefijo = "";
                    if ($aux["cod_archivo"] < 10000){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 1000){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 100){
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 10){
                        $prefijo .= "0";
                    }
                    $prefijo .= $aux["cod_archivo"] . " - ";
                    $result[$k]["prefijo"] = $prefijo;
                }

                return $result;
            }
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
    
    public function getEventosOtp($otp){
        try {
            $sql = "SELECT ota.*,
                otp.ot_detalle_id,
                otp.prod_estandar_id,
                otp.prod_personalizado_id,
                otp.standar,
                (select a.descripcion from eventos a where a.codigo = ota.evento_id) as evento
             FROM orden_trabajos_eventos ota, orden_trabajos_produccion otp where ota.ot_produccion_id = otp.codigo and ota.ot_produccion_id = " . intval($otp) . " order by ota.fecha_m desc, ota.codigo desc;";
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
    
    public function getOtp($otp){
        try {
            $sql = "SELECT 
            otp.*,
            (select descripcion from productos_estandar s where s.codigo = otp.prod_estandar_id) as prod_standar,
            (select descripcion from productos_personalizados s where s.codigo = otp.prod_personalizado_id) as prod_personalizado,
            (select descripcion from unidades s where s.codigo = otp.unidad_id) as unidad
            FROM orden_trabajos_produccion otp WHERE otp.codigo = " . intval($otp) . " ";
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
    
    public function getOtd($otp){
        try {
            $sql = "SELECT 
            otd.*
            FROM orden_trabajos_detalles otd WHERE otd.codigo = " . intval($otp) . " ";
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
    
    public function getOt($ot){
        try {
            $sql = "SELECT 
            otd.*
            FROM orden_trabajos otd WHERE otd.codigo = " . intval($ot) . " ";
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

    public function cambiar_estadoProductoParam($estado, $codigo, $nivel){
        try {
            if ($nivel == 4){
                $tabla = "productos_nivel_d";
            } else {
                $tabla = "productos_nivel_f";
            }
            if ($nivel == 3){
                $tabla = "productos_nivel_c";
            }
            if ($nivel == 2){
                $tabla = "productos_nivel_b";
            }
            if ($nivel == 1){
                $tabla = "productos_nivel_a";
            }
            
            $hoy = date("Y-m-d H:i:s");
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE ' . $tabla . ' set '
                                            . 'ing_estado = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(3, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(4, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return var_dump($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }

    public function cambiar_estadoProducto($estado, $codigo, $n1, $n2, $n3, $n4){
        try {
            if ($n4 == 0){
                $tabla = "productos_nivel_d";
            } else {
                $tabla = "productos_nivel_e";
            }
            if ($n3 == 0){
                $tabla = "productos_nivel_c";
            }
            if ($n2 == 0){
                $tabla = "productos_nivel_b";
            }
            if ($n1 == 0){
                $tabla = "productos_nivel_a";
            }
            
            $hoy = date("Y-m-d H:i:s");
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE ' . $tabla . ' set '
                                            . 'ing_estado = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(3, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(4, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return var_dump($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }

    public function cambiar_estadoOpcion($estado, $codigo, $n1, $n2, $n3, $n4){
        try {
                $tabla = "productos_nivel_f";
            
            $hoy = date("Y-m-d H:i:s");
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE ' . $tabla . ' set '
                                            . 'ing_estado = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');            
            
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(3, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(4, $codigo, PDO::PARAM_INT);
            if($stmt->execute()){
                $this->conn->commit();
                return 0;
            }  else {
                $this->conn->rollBack();
                return var_dump($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
    
    public function mostrarArchivos($estado, $codigo, $nivel) {
        try {
            if ($nivel == 4){
                $tabla = "cod_prod_nd";
            } else {
                $tabla = "cod_prod_nf";
            }
            if ($nivel == 3){
                $tabla = "cod_prod_nc";
            }
            if ($nivel == 2){
                $tabla = "cod_prod_nb";
            }
            if ($nivel == 1){
                $tabla = "cod_prod_na";
            }
            $sql = "SELECT 
            *
            FROM archivos a WHERE a." . $tabla . " = " . intval($codigo) . " order by descripcion ";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) { 
                $result = $query->fetchAll();
                
                foreach($result as $k => $aux){
                    $prefijo = "";
                    if ($aux["codigo"] < 10000){
                        $prefijo .= "0";
                    }
                    if ($aux["codigo"] < 1000){
                        $prefijo .= "0";
                    }
                    if ($aux["codigo"] < 100){
                        $prefijo .= "0";
                    }
                    if ($aux["codigo"] < 10){
                        $prefijo .= "0";
                    }
                    $prefijo .= $aux["codigo"] . " - ";
                    $result[$k]["prefijo"] = $prefijo;
                }

                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();

            return $error;
        }
    }
    public function getOtsportada(){
        try {
            $sql = "SELECT *,
                        CAST(nro_serie AS UNSIGNED) serie,
                        concat('#', nro_serie, ': ', cliente, ' - ', fecha) as descripcion
                    FROM orden_trabajos ORDER BY serie desc;";
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