<?php
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/bd/conexion.php";

class Portada_Model
{
    private static $instancia;
    private $conn;
    public function __construct()
    {
        try {
            $this->conn = Conexion::singleton_conexion();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_portada()
    {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    public function obtenerCamposprimerosdetalles($ot)
    {
        try {
            $sql = "SELECT usuario_m ,nro_serie,cliente, codigo , fecha_entrega FROM orden_trabajos  WHERE codigo='" . $ot . "';";
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

    public function obtenerCampossectoressecciones($codPP, $valorSeleccion)
    {
        try {
            ///ot=3,$otd=11;
            // $sql="SELECT otd.seccion as seccion,otd.sector,otd.item_vendido as item,otd.observaciones,pp.descripcion,otd.cantidad,pp.codigo as cod_prod_personalizado FROM orden_trabajos ot INNER JOIN orden_trabajos_detalles otd ON ot.codigo=otd.orden_trabajo_id INNER JOIN orden_trabajos_produccion otp ON otd.codigo=otp.ot_detalle_id INNER JOIN productos_personalizados pp ON otp.prod_personalizado_id=pp.codigo WHERE ot.codigo='".$ot."' AND otd.codigo='".$otd."'";
            if ($valorSeleccion == 0) {
                $sql = "SELECT otd.seccion as seccion,otd.sector,otd.item_vendido as item,otd.observaciones,pp.descripcion,otd.cantidad,pp.codigo as cod_prod_personalizado,otp.prod_personalizado_id as cod_pers ,otp.prod_estandar_id as cod_estandar FROM orden_trabajos ot INNER JOIN orden_trabajos_detalles otd ON ot.codigo=otd.orden_trabajo_id INNER JOIN orden_trabajos_produccion otp ON otd.codigo=otp.ot_detalle_id INNER JOIN productos_personalizados pp ON otp.prod_personalizado_id=pp.codigo WHERE pp.codigo='" . $codPP . "'";
            } else {
                $sql = "SELECT  otp.ot_detalle_id as id_detalle, otd.seccion as seccion,otd.sector,otd.item_vendido as item,otd.observaciones,pp.descripcion,otd.cantidad,pp.codigo as cod_prod_personalizado,otp.prod_personalizado_id as cod_pers,otp.prod_estandar_id as cod_estandar FROM orden_trabajos ot INNER JOIN orden_trabajos_detalles otd ON ot.codigo=otd.orden_trabajo_id INNER JOIN orden_trabajos_produccion otp ON otd.codigo=otp.ot_detalle_id INNER JOIN productos_estandar pp ON otp.prod_estandar_id=pp.codigo WHERE otp.codigo='" . $codPP . "'";
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
    public function getOtsportada()
    {
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
    public function productoDetalle($otprodDetalle, $valorSeleccion)
    {
        try {
            if ($valorSeleccion == 0) {
                $sql = "SELECT * FROM orden_trabajos_produccion otp INNER JOIN productos_personalizados pp ON otp.prod_personalizado_id=pp.codigo WHERE pp.codigo='" . $otprodDetalle . "'";
            } else {
                $sql = "SELECT * FROM orden_trabajos_produccion otp INNER JOIN productos_estandar pp ON otp.prod_estandar_id=pp.codigo WHERE otp.codigo='" . $otprodDetalle . "'";
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
    public function getArchivostablapersonalizado($idProductopersonalizado)
    {
        try {
            $sql = "SELECT * FROM archivos  WHERE cod_prod_personalizado_id=" . intval($idProductopersonalizado) . ";";

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
    public function getArchivosAll()
    {
        try {
            $sql = "SELECT * FROM archivos order by descripcion;";
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
    public function getarchivosOtp($otp)
    {
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
                foreach ($result as $k => $aux) {
                    $prefijo = "";
                    if ($aux["cod_archivo"] < 10000) {
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 1000) {
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 100) {
                        $prefijo .= "0";
                    }
                    if ($aux["cod_archivo"] < 10) {
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


    public function getPartesProduccions($ot)
    {
        try {
            // $desde = ($pagina - 1) * $registros;
            $sql = "SELECT 
                        otp.*,
                        (select descripcion from productos_estandar s where s.codigo = otp.prod_estandar_id) as prod_standar,
                        (select descripcion from productos_personalizados s where s.codigo = otp.prod_personalizado_id) as prod_personalizado,
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
}
