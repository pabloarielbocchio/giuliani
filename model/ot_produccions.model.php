<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class Ot_produccionsModel {
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

    public static function singleton_ot_produccions() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_produccions(){
        try {
            $sql = "SELECT count(*) FROM orden_trabajos_produccion;";
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
            $sql = "SELECT 
                        otp.*,
                        (select descripcion from productos_nivel_d s where s.codigo = otp.prod_estandar_id) as prod_standar,
                        (select descripcion from productos_personalizados s where s.codigo = otp.prod_personalizado_id) as prod_personalizado,
                        (select descripcion from unidades s where s.codigo = otp.unidad_id) as unidad,
                        (select descripcion from secciones s where s.codigo = otd.seccion_id) as seccion,
                        (select descripcion from sectores s where s.codigo = otd.sector_id) as sector,
                        (select abrev from estados s where s.codigo = otp.estado_id) as estado,
                        (select descripcion from prioridades s where s.codigo = otp.prioridad_id) as prioridad
                    FROM orden_trabajos_produccion otp, orden_trabajos_detalles otd WHERE otp.ot_detalle_id = otd.codigo and (otp.observaciones like '%" . $busqueda . "%') ORDER BY " . $orderby . " " . $sentido;
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
    
    public function deleteOt_produccionEstados($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from orden_trabajos_estados where ot_prod_id = ? and (ingenieria is null or ingenieria = 0)');
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
    
    public function deleteOt_produccion($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from orden_trabajos_produccion where codigo = ?');
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
    
    public function updateOt_produccion($codigo, $avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos_produccion set '
                                            . 'ot_detalle_id = ? , '
                                            . 'prod_estandar_id = ? , '
                                            . 'prod_personalizado_id = ? , '
                                            . 'unidad_id = ? , '
                                            . 'estado_id = ? , '
                                            . 'prioridad_id = ? , '
                                            . 'avance = ? , '
                                            . 'cantidad = ? , '
                                            . 'standar = ? , '
                                            . 'observaciones = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');     
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
            $stmt->bindValue(13, $codigo, PDO::PARAM_INT);
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
    
    
    public function updateOt_produccionEstadoAll($codigo, $atributo, $avance, $estado){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos_estados set '
                                            . 'estado_id = ? , '
                                            . 'avance = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where ot_prod_id = ? ' );     
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $avance, PDO::PARAM_STR);
            $stmt->bindValue(3, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(4, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(5, $codigo, PDO::PARAM_INT);
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
    
    public function updateOt_produccionEstado($codigo, $atributo, $avance, $estado, $code, $ing_alcance, $ing_planos, $observaciones = null){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            if (!$observaciones){
                $observaciones = "Actualizacion Estado OT Produccion."; 
            }
            $stmt = $this->conn->prepare('UPDATE orden_trabajos_estados set '
                                            . 'estado_id = ? , '
                                            . 'avance = ? , '
                                            . 'ing_alcance = ? , '
                                            . 'ing_planos = ? , '
                                            . 'observaciones = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where ot_prod_id = ? and codigo = ?' );     
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $avance, PDO::PARAM_STR);
            $stmt->bindValue(3, $ing_alcance, PDO::PARAM_INT);
            $stmt->bindValue(4, $ing_planos, PDO::PARAM_INT);
            $stmt->bindValue(5, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(6, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(7, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(8, $codigo, PDO::PARAM_INT);
            $stmt->bindValue(9, $code, PDO::PARAM_INT);
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
    
    public function insertOt_produccionEstado($codigo, $atributo, $avance, $estado, $code, $ing_alcance, $ing_planos, $destino, $observaciones = null){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            if (!$observaciones){
                $observaciones = "Actualizacion Estado OT Produccion."; 
            }
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_estados (estado_id, avance, ot_prod_id, destino_id, observaciones, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $estado, PDO::PARAM_INT);
            $stmt->bindValue(2, $avance, PDO::PARAM_STR);
            $stmt->bindValue(3, $codigo, PDO::PARAM_INT);
            $stmt->bindValue(4, $destino, PDO::PARAM_INT);
            $stmt->bindValue(5, $observaciones, PDO::PARAM_STR);
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
    
    public function getOt_produccion($codigo){
        try {
            $sql = " SELECT otp.*, ";
            $sql.= " (select descripcion from productos_estandar s where s.codigo = otp.prod_estandar_id) as prod_standar, ";
            $sql.= " (select descripcion from productos_personalizados s where s.codigo = otp.prod_personalizado_id) as prod_personalizado ";
            $sql.= " FROM orden_trabajos_produccion otp WHERE otp.codigo = " . $codigo . ";";
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
    
    public function getOt_produccionEstadoCode($code){
        try {
            $sql = "SELECT * FROM orden_trabajos_estados WHERE codigo = " . $code . ";";
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
        
    public function getOtpsArchivosDestinos($cod_otp){
        try {
            $sql  = " SELECT COUNT(*) AS cuenta, d.codigo, d.descripcion ";
            $sql .= " FROM orden_trabajos_archivos ota, archivos a, archivo_destinos ad, destinos d ";
            $sql .= " WHERE ota.archivo_id = a.codigo AND a.codigo = ad.archivo_id ";
            $sql .= " AND d.codigo = ad.destino_id AND ot_produccion_id = " . intval($cod_otp);
            $sql .= " GROUP BY d.codigo, d.descripcion;";
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
    
    public function getOt_produccionDestino($codigo, $destino){
        try {
            $sql = "SELECT * FROM orden_trabajos_estados WHERE ot_prod_id = " . $codigo . " and produccion = 1 and destino_id = " . $destino . " ;";
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
    
    public function getOt_produccionEstado($codigo, $atributo, $destino){
        try {
            $sql = "SELECT * FROM orden_trabajos_estados WHERE ot_prod_id = " . intval($codigo) . " and " . $atributo . " = 1 ";
            if ($destino > 0){
                $sql .= " and destino_id = " . intval($destino);
            }
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
    
    public function getSecciones(){
        try {
            $sql = "SELECT * FROM secciones ORDER BY descripcion;";
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
    
    public function getSectores(){
        try {
            $sql = "SELECT * FROM sectores ORDER BY descripcion;";
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
    
    public function getEstados(){
        try {
            $sql = "SELECT * FROM estados ORDER BY descripcion;";
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
    
    public function getEstado($codigo){
        try {
            $sql = "SELECT * FROM estados where codigo = " . intval($codigo) . " ORDER BY descripcion;";
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
    
    public function getStandards(){
        try {
            $sql = "SELECT * FROM productos_nivel_d ORDER BY descripcion;";
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
            $sql = "SELECT * FROM unidades ORDER BY descripcion;";
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
    
    public function getPersonalizados(){
        try {
            $sql = "SELECT * FROM productos_personalizados ORDER BY descripcion;";
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

    public function getOts(){
        try {
            $sql = "SELECT *,
                        concat('#', nro_serie, ': ', cliente, ' - ', fecha) as descripcion
                    FROM orden_trabajos ORDER BY codigo;";
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

    public function getOtsDetalles(){
        try {
            $sql = "SELECT 
                        otd.codigo as codigo,
                        concat('Detail: ', otd.codigo, ' | #', ot.nro_serie, ': ', ot.cliente, ' - ', ot.fecha) as descripcion
                    FROM orden_trabajos ot, orden_trabajos_detalles otd
                    WHERE otd.orden_trabajo_id = ot.codigo ORDER BY otd.codigo;";
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
    
    public function addOt_evento($detalle, $produccion, $evento, $destino, $observaciones){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_eventos (evento_id, ot_detalle_id, ot_produccion_id, destino_id, observaciones, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $evento, PDO::PARAM_INT);
            $stmt->bindValue(2, $detalle, PDO::PARAM_INT);
            $stmt->bindValue(3, $produccion, PDO::PARAM_INT);
            $stmt->bindValue(4, $destino, PDO::PARAM_INT);
            $stmt->bindValue(5, $observaciones, PDO::PARAM_STR);
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
        
    public function getLastOtProduccion(){
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
    
    public function getOtps($ult_detalle){
        try {
            $sql = "SELECT * FROM orden_trabajos_produccion WHERE ot_detalle_id = " . $ult_detalle . ";";
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
    
    public function getOtpsDestinos($codigo){
        try {
            $sql = "SELECT * FROM orden_trabajos_estados WHERE ot_prod_id = " . $codigo . ";";
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
    
    public function getOtpsDestinoSingle($otp, $destino){
        try {
            $sql = "SELECT * FROM orden_trabajos_estados WHERE ot_prod_id = " . $otp . " and destino_id = " . $destino . " ;";
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
    
    public function ejecutarSql($sql){
        try {
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    
}	
?>