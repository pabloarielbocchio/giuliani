<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class Ot_detallesModel {
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

    public static function singleton_ot_detalles() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_detalles(){
        try {
            $sql = "SELECT count(*) FROM orden_trabajos_detalles;";
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
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot, $estado = -1){
        try {
            $desde = ($pagina - 1) * $registros;
            $sql = " SELECT 
                        otd.*,/*
                        (select descripcion from secciones s where s.codigo = otd.seccion_id) as seccion,
                        (select descripcion from sectores s where s.codigo = otd.sector_id) as sector,*/
                        (select abrev from estados s where s.codigo = otd.estado_id) as estado,
                        (select descripcion from prioridades s where s.codigo = otd.prioridad_id) as prioridad
                     FROM orden_trabajos_detalles otd WHERE 1 = 1 ";                     
            if ($estado == 1){
                $sql .= " and otd.finalizada = " . intval($estado);
            }
            if ($estado == 0){
                $sql .= " and (otd.finalizada is null or otd.finalizada = " . intval($estado) . ")";
            }
            $sql.= " and otd.orden_trabajo_id = " . intval($ot) . " and (otd.observaciones like '%" . $busqueda . "%' or otd.item_vendido like '%" . $busqueda . "%') ORDER BY " . $orderby . " " . $sentido;
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
    
    public function getPartesProduccions($ot){
        try {
            $desde = ($pagina - 1) * $registros;
            $sql = "SELECT 
                        otp.*,
                        (select oracle from productos_estandar s where s.codigo = otp.prod_estandar_id) as oracle_standar,
                        (select oracle from productos_personalizados s where s.codigo = otp.prod_personalizado_id) as oracle_personalizado,
                        (select if(oracle IS NOT null and oracle != '', CONCAT(descripcion, ' (NS# ', oracle, ')'), descripcion) AS descripcion from productos_estandar s where s.codigo = otp.prod_estandar_id) as prod_standar,
                        (select 
                            CONCAT(
                                descripcion,
                                IF(oracle IS NOT NULL AND oracle != '' OR sarah IS NOT NULL AND sarah != '' OR demanda IS NOT NULL AND demanda != '', ' (', ''),
                                IF(oracle IS NOT NULL AND oracle != '', CONCAT('NS# ', oracle), ''),
                                IF(oracle IS NOT NULL AND oracle != '' AND (sarah IS NOT NULL AND sarah != '' OR demanda IS NOT NULL AND demanda != ''), ', ', ''),
                                IF(sarah IS NOT NULL AND sarah != '', CONCAT('OF# ', sarah), ''),
                                IF(sarah IS NOT NULL AND sarah != '' AND demanda IS NOT NULL AND demanda != '', ', ', ''),
                                IF(demanda IS NOT NULL AND demanda != '', CONCAT('PD# ', demanda), ''),
                                IF(oracle IS NOT NULL AND oracle != '' OR sarah IS NOT NULL AND sarah != '' OR demanda IS NOT NULL AND demanda != '', ')', '')
                            ) AS descripcion 
                        from productos_personalizados s 
                        where s.codigo = otp.prod_personalizado_id) as prod_personalizado,
                        (select descrip_abrev from unidades s where s.codigo = otp.unidad_id) as unidad,
                        (select abrev from estados s where s.codigo = otp.estado_id) as estado,
                        (select descripcion from prioridades s where s.codigo = otp.prioridad_id) as prioridad
                    FROM orden_trabajos_produccion otp, orden_trabajos_detalles otd WHERE otd.orden_trabajo_id = " . intval($ot) . " and otp.ot_detalle_id = otd.codigo  ";
        
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
    
    
    public function getPartesProduccionsEstados($ot){
        try {
            $desde = ($pagina - 1) * $registros;
            $sql = "SELECT 
                        ote.*,                        
                        (select abrev from estados s where s.codigo = ote.estado_id) as estado,
                        (select descripcion from destinos s where s.codigo = ote.destino_id) as destino
                    FROM orden_trabajos_estados ote, orden_trabajos_produccion otp, orden_trabajos_detalles otd WHERE ote.ot_prod_id = otp.codigo and otd.orden_trabajo_id = " . intval($ot) . " and otp.ot_detalle_id = otd.codigo  ";
     
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
    
    public function deleteOt_detalle($codigo){
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('DELETE from orden_trabajos_detalles where codigo = ?');
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
    
    public function addOt_detalle($item,$cantidad,$seccion,$sector,$estado,$prioridad,$ot,$pu,$pucant,$puneto,$clasificacion,$observaciones){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('INSERT INTO orden_trabajos_detalles (orden_trabajo_id, seccion, sector, estado_id, prioridad_id, item_vendido, cantidad, pu, pu_cant, pu_neto, clasificacion, observaciones, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $ot, PDO::PARAM_INT);
            $stmt->bindValue(2, $seccion, PDO::PARAM_STR);
            $stmt->bindValue(3, $sector, PDO::PARAM_STR);
            $stmt->bindValue(4, $estado, PDO::PARAM_INT);
            $stmt->bindValue(5, $prioridad, PDO::PARAM_INT);
            $stmt->bindValue(6, $item, PDO::PARAM_STR);
            $stmt->bindValue(7, $cantidad, PDO::PARAM_STR);
            $stmt->bindValue(8, $pu, PDO::PARAM_STR);
            $stmt->bindValue(9, $pucant, PDO::PARAM_STR);
            $stmt->bindValue(10, $puneto, PDO::PARAM_STR);
            $stmt->bindValue(11, $clasificacion, PDO::PARAM_STR);
            $stmt->bindValue(12, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(13, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(14, $hoy, PDO::PARAM_STR);
            
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
    
    public function updateOt_detalle($codigo, $item,$cantidad,$seccion,$sector,$estado,$prioridad,$ot,$pu,$pucant,$puneto,$clasificacion,$observaciones){
        $hoy = date("Y-m-d H:i:s");
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare('UPDATE orden_trabajos_detalles set '
                                            . 'orden_trabajo_id = ? , '
                                            . 'seccion = ? , '
                                            . 'sector = ? , '
                                            . 'estado_id = ? , '
                                            . 'prioridad_id = ? , '
                                            . 'item_vendido = ? , '
                                            . 'cantidad = ? , '
                                            . 'pu = ? , '
                                            . 'pu_cant = ? , '
                                            . 'pu_neto = ? , '
                                            . 'clasificacion = ? , '
                                            . 'observaciones = ? , '
                                            . 'usuario_m = ?, '
                                            . 'fecha_m = ? '
                                            . ' where codigo = ?');     
            $stmt->bindValue(1, $ot, PDO::PARAM_INT);
            $stmt->bindValue(2, $seccion, PDO::PARAM_STR);
            $stmt->bindValue(3, $sector, PDO::PARAM_STR);
            $stmt->bindValue(4, $estado, PDO::PARAM_INT);
            $stmt->bindValue(5, $prioridad, PDO::PARAM_INT);
            $stmt->bindValue(6, $item, PDO::PARAM_STR);
            $stmt->bindValue(7, $cantidad, PDO::PARAM_STR);
            $stmt->bindValue(8, $pu, PDO::PARAM_STR);
            $stmt->bindValue(9, $pucant, PDO::PARAM_STR);
            $stmt->bindValue(10, $puneto, PDO::PARAM_STR);
            $stmt->bindValue(11, $clasificacion, PDO::PARAM_STR);
            $stmt->bindValue(12, $observaciones, PDO::PARAM_STR);
            $stmt->bindValue(13, $_SESSION["usuario"], PDO::PARAM_STR);
            $stmt->bindValue(14, $hoy, PDO::PARAM_STR);
            $stmt->bindValue(15, $codigo, PDO::PARAM_INT);
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
    
    public function getOt_header($codigo){
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
    
    public function getOt_detalle($codigo){
        try {
            $sql = "SELECT * FROM orden_trabajos_detalles WHERE codigo = " . $codigo . ";";
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

    public function getOts(){
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

    public function getUnidades(){
        try {
            $sql = "SELECT *
                    FROM unidades ORDER BY descrip_abrev;";
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
    
    public function getRoles(){
        try {
            $sql = "SELECT * FROM roles order by descripcion;";
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
        
    public function getLastOtDetalle(){
        try {
            $sql = "SELECT * FROM orden_trabajos_detalles order by codigo desc limit 1;";
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
    
    public function getMenuDestinos($rol){
        try {
            $sql = "select 
                        m.*,
                        d.descripcion as descripcion
                    from 
                        roles_destinos m,
                        destinos d
                    where
                        m.destino_id = d.codigo and
                        m.rol_id = " . intval($rol) . " 
                    order by
                        d.orden asc;";
            if ($rol == 1){
                $sql = "select 
                            d.codigo as destino_id,
                            d.descripcion as descripcion
                        from 
                            destinos d
                        order by
                            d.orden asc;";
            }
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    
    
    public function getMotivos(){
        try {
            $sql = "select 
                        *
                    from 
                        motivos
                    order by
                        descripcion
                    ;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    
    
    public function getUsuarios(){
        try {
            $sql = "select 
                        *
                    from 
                        usuarios
                    order by
                        usuario
                    ;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    
    
    public function getArchivos(){
        try {
            $sql = "select 
                        a.*,
                        ota.ot_produccion_id,
                        ota.ot_detalle_id,
                        ota.ot_id
                    from 
                        archivos a,
                        orden_trabajos_archivos ota
                    where 
                        ota.archivo_id = a.codigo
                    ;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
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
    
    public function getOtArchivos($cod_ot){
        try {
            $sql = "select 
                        a.*,
                        ota.ot_produccion_id,
                        ota.ot_detalle_id,
                        ota.ot_id
                    from 
                        archivos a,
                        orden_trabajos_archivos ota
                    where 
                        ota.archivo_id = a.codigo
                        and (
                            ota.ot_produccion_id in (select codigo from orden_trabajos_produccion where ot_detalle_id in 
                                (select codigo from orden_trabajos_detalles where orden_trabajo_id = " . intval($cod_ot) . ")
                            ) or
                            (
                            ota.ot_detalle_id in (select codigo from orden_trabajos_detalles where orden_trabajo_id = " . intval($cod_ot) . ")
                            ) or
                            (
                            ota.ot_id in (" . intval($cod_ot) . ")
                            )
                        )
                    ;";
            $sql = "select 
                        a.*,
                        ota.ot_produccion_id,
                        ota.ot_detalle_id,
                        ota.ot_id
                    from 
                        archivos a,
                        orden_trabajos_archivos ota
                    where 
                        ota.archivo_id = a.codigo
                        and (
                            ota.ot_produccion_id in (select codigo from orden_trabajos_produccion where ot_detalle_id in 
                                (select codigo from orden_trabajos_detalles where id = " . intval($cod_ot) . ")
                            ) or
                            (
                            ota.ot_detalle_id in (select codigo from orden_trabajos_detalles where id = " . intval($cod_ot) . ")
                            ) 
                        )
                    ;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
        
    }    
    
    public function getOt($cod_ot){
        try {
            $sql = "select 
                        *
                    from 
                        orden_trabajos_detalles
                    where 
                        codigo = " . intval($cod_ot) . "
                    ;";
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