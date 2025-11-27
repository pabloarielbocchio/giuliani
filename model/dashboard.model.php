<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

class DashboardModel {
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

    public static function singleton_dashboard() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getDashboardEjecutivo($fecha_desde, $fecha_hasta, $proyecto, $usuario){
        try {
            // Calcular período anterior para comparación
            $fecha_desde_obj = new DateTime($fecha_desde);
            $fecha_hasta_obj = new DateTime($fecha_hasta);
            $diferencia = $fecha_desde_obj->diff($fecha_hasta_obj)->days;
            
            $fecha_desde_anterior = date('Y-m-d', strtotime($fecha_desde . ' -' . ($diferencia + 1) . ' days'));
            $fecha_hasta_anterior = date('Y-m-d', strtotime($fecha_desde . ' -1 day'));
            
            // Construir filtros
            $filtro_proyecto = $proyecto > 0 ? " AND ot.codigo = " . intval($proyecto) : "";
            $filtro_usuario = $usuario != '0' ? " AND a.usuario_m = '" . addslashes($usuario) . "'" : "";
            $filtro_usuario_eventos = $usuario != '0' ? " AND ote.usuario_m = '" . addslashes($usuario) . "'" : "";
            
            // KPIs - Archivos Subidos (usando orden_trabajos_archivos con las 3 relaciones posibles)
            $sql_uploads = "SELECT COUNT(DISTINCT a.codigo) as total 
                           FROM orden_trabajos_archivos ota
                           INNER JOIN archivos a ON a.codigo = ota.archivo_id
                           LEFT JOIN orden_trabajos ot1 ON ota.ot_id = ot1.codigo
                           LEFT JOIN orden_trabajos_detalles otd ON ota.ot_detalle_id = otd.codigo
                           LEFT JOIN orden_trabajos ot2 ON otd.orden_trabajo_id = ot2.codigo
                           LEFT JOIN orden_trabajos_produccion otp ON ota.ot_produccion_id = otp.codigo
                           LEFT JOIN orden_trabajos_detalles otd2 ON otp.ot_detalle_id = otd2.codigo
                           LEFT JOIN orden_trabajos ot3 ON otd2.orden_trabajo_id = ot3.codigo
                           WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                           AND (ot1.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                OR ot2.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario;
            $query = $this->conn->prepare($sql_uploads);
            $query->execute();
            $uploads_actual = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sql_uploads_anterior = "SELECT COUNT(DISTINCT a.codigo) as total 
                                     FROM orden_trabajos_archivos ota
                                     INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                     LEFT JOIN orden_trabajos ot1 ON ota.ot_id = ot1.codigo
                                     LEFT JOIN orden_trabajos_detalles otd ON ota.ot_detalle_id = otd.codigo
                                     LEFT JOIN orden_trabajos ot2 ON otd.orden_trabajo_id = ot2.codigo
                                     LEFT JOIN orden_trabajos_produccion otp ON ota.ot_produccion_id = otp.codigo
                                     LEFT JOIN orden_trabajos_detalles otd2 ON otp.ot_detalle_id = otd2.codigo
                                     LEFT JOIN orden_trabajos ot3 ON otd2.orden_trabajo_id = ot3.codigo
                                     WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde_anterior . "' AND '" . $fecha_hasta_anterior . "'
                                     AND (ot1.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                          OR ot2.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                          OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario;
            $query = $this->conn->prepare($sql_uploads_anterior);
            $query->execute();
            $uploads_anterior = $query->fetch(PDO::FETCH_ASSOC)['total'];
            $variacion_uploads = $uploads_anterior > 0 ? (($uploads_actual - $uploads_anterior) / $uploads_anterior) * 100 : 0;
            
            // KPIs - Descargas (eventos tipo 13 según descargas.php)
            $sql_downloads = "SELECT COUNT(*) as total 
                             FROM orden_trabajos_eventos ote
                             LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                             LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                             WHERE ote.evento_id in (13, 5) 
                             AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'" . $filtro_proyecto . $filtro_usuario_eventos;
            $query = $this->conn->prepare($sql_downloads);
            $query->execute();
            $downloads_actual = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sql_downloads_anterior = "SELECT COUNT(*) as total 
                                      FROM orden_trabajos_eventos ote
                                      LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                      LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                                      WHERE ote.evento_id in (13, 5) 
                                      AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde_anterior . "' AND '" . $fecha_hasta_anterior . "'" . $filtro_proyecto . $filtro_usuario_eventos;
            $query = $this->conn->prepare($sql_downloads_anterior);
            $query->execute();
            $downloads_anterior = $query->fetch(PDO::FETCH_ASSOC)['total'];
            $variacion_downloads = $downloads_anterior > 0 ? (($downloads_actual - $downloads_anterior) / $downloads_anterior) * 100 : 0;
            
            // KPIs - Total OT
            $sql_ots = "SELECT COUNT(*) as total 
                       FROM orden_trabajos ot
                       WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'" . ($proyecto > 0 ? " AND ot.codigo = " . intval($proyecto) : "");
            $query = $this->conn->prepare($sql_ots);
            $query->execute();
            $ots_actual = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sql_ots_anterior = "SELECT COUNT(*) as total 
                                FROM orden_trabajos ot
                                WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde_anterior . "' AND '" . $fecha_hasta_anterior . "'" . ($proyecto > 0 ? " AND ot.codigo = " . intval($proyecto) : "");
            $query = $this->conn->prepare($sql_ots_anterior);
            $query->execute();
            $ots_anterior = $query->fetch(PDO::FETCH_ASSOC)['total'];
            $variacion_ots = $ots_anterior > 0 ? (($ots_actual - $ots_anterior) / $ots_anterior) * 100 : 0;
            
            // KPIs - Usuarios Activos/Inactivos
            $sql_usuarios_activos = "SELECT COUNT(DISTINCT a.usuario_m) as total 
                                    FROM orden_trabajos_archivos ota
                                    INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                    LEFT JOIN orden_trabajos ot1 ON ota.ot_id = ot1.codigo
                                    LEFT JOIN orden_trabajos_detalles otd ON ota.ot_detalle_id = otd.codigo
                                    LEFT JOIN orden_trabajos ot2 ON otd.orden_trabajo_id = ot2.codigo
                                    LEFT JOIN orden_trabajos_produccion otp ON ota.ot_produccion_id = otp.codigo
                                    LEFT JOIN orden_trabajos_detalles otd2 ON otp.ot_detalle_id = otd2.codigo
                                    LEFT JOIN orden_trabajos ot3 ON otd2.orden_trabajo_id = ot3.codigo
                                    WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                    AND (ot1.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                         OR ot2.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                         OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario;
            $query = $this->conn->prepare($sql_usuarios_activos);
            $query->execute();
            $usuarios_activos = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sql_usuarios_total = "SELECT COUNT(*) as total FROM usuarios WHERE fecha_baja IS NULL";
            $query = $this->conn->prepare($sql_usuarios_total);
            $query->execute();
            $usuarios_total = $query->fetch(PDO::FETCH_ASSOC)['total'];
            $usuarios_inactivos = $usuarios_total - $usuarios_activos;
            
            // Gráfico de Actividad - Uploads y Downloads por día
            $sql_uploads_diario = "SELECT 
                                     DATE(a.fecha_hora) as fecha,
                                     COUNT(DISTINCT a.codigo) as uploads
                                   FROM orden_trabajos_archivos ota
                                   INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                   LEFT JOIN orden_trabajos ot1 ON ota.ot_id = ot1.codigo
                                   LEFT JOIN orden_trabajos_detalles otd ON ota.ot_detalle_id = otd.codigo
                                   LEFT JOIN orden_trabajos ot2 ON otd.orden_trabajo_id = ot2.codigo
                                   LEFT JOIN orden_trabajos_produccion otp ON ota.ot_produccion_id = otp.codigo
                                   LEFT JOIN orden_trabajos_detalles otd2 ON otp.ot_detalle_id = otd2.codigo
                                   LEFT JOIN orden_trabajos ot3 ON otd2.orden_trabajo_id = ot3.codigo
                                   WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                   AND (ot1.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                        OR ot2.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                        OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . "
                                   GROUP BY DATE(a.fecha_hora)
                                   ORDER BY fecha";
            
            $sql_downloads_diario = "SELECT 
                                       DATE(ote.fecha_m) as fecha,
                                       COUNT(*) as downloads
                                     FROM orden_trabajos_eventos ote
                                     LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                     LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                                     WHERE ote.evento_id in (13, 5) 
                                     AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'" . $filtro_proyecto . $filtro_usuario_eventos . "
                                     GROUP BY DATE(ote.fecha_m)
                                     ORDER BY fecha";
            
            $query = $this->conn->prepare($sql_uploads_diario);
            $query->execute();
            $actividad_uploads = $query->fetchAll(PDO::FETCH_ASSOC);
            
            $query = $this->conn->prepare($sql_downloads_diario);
            $query->execute();
            $actividad_downloads = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 5 Usuarios que Suben
            $sql_top_uploads = "SELECT 
                                  a.usuario_m as usuario,
                                  COUNT(DISTINCT a.codigo) as cantidad
                                FROM orden_trabajos_archivos ota
                                INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                LEFT JOIN orden_trabajos ot1 ON ota.ot_id = ot1.codigo
                                LEFT JOIN orden_trabajos_detalles otd ON ota.ot_detalle_id = otd.codigo
                                LEFT JOIN orden_trabajos ot2 ON otd.orden_trabajo_id = ot2.codigo
                                LEFT JOIN orden_trabajos_produccion otp ON ota.ot_produccion_id = otp.codigo
                                LEFT JOIN orden_trabajos_detalles otd2 ON otp.ot_detalle_id = otd2.codigo
                                LEFT JOIN orden_trabajos ot3 ON otd2.orden_trabajo_id = ot3.codigo
                                WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                AND (ot1.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                     OR ot2.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . " 
                                     OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . "
                                GROUP BY a.usuario_m
                                ORDER BY cantidad DESC
                                LIMIT 5";
            $query = $this->conn->prepare($sql_top_uploads);
            $query->execute();
            $top_uploads = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 5 Usuarios que Descargan
            $sql_top_downloads = "SELECT 
                                    ote.usuario_m as usuario,
                                    COUNT(*) as cantidad
                                  FROM orden_trabajos_eventos ote
                                  LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                  LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                                  WHERE ote.evento_id in (13, 5) 
                                  AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'" . $filtro_proyecto . $filtro_usuario_eventos . "
                                  GROUP BY ote.usuario_m
                                  ORDER BY cantidad DESC
                                  LIMIT 5";
            $query = $this->conn->prepare($sql_top_downloads);
            $query->execute();
            $top_downloads = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Estados de OT - Relación: orden_trabajos_estados => orden_trabajos_produccion => orden_trabajos_detalles => orden_trabajos
            $sql_estados_ot = "SELECT 
                                COALESCE(e.descripcion, e.abrev, 'Sin Estado') as estado,
                                COUNT(DISTINCT ot.codigo) as cantidad
                              FROM orden_trabajos ot
                              INNER JOIN orden_trabajos_detalles otd ON otd.orden_trabajo_id = ot.codigo
                              INNER JOIN orden_trabajos_produccion otp ON otp.ot_detalle_id = otd.codigo
                              INNER JOIN orden_trabajos_estados ote ON ote.ot_prod_id = otp.codigo
                              INNER JOIN estados e ON e.codigo = ote.estado_id
                              WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'" . ($proyecto > 0 ? " AND ot.codigo = " . intval($proyecto) : "") . "
                              GROUP BY e.codigo, e.descripcion, e.abrev
                              ORDER BY cantidad DESC";
            $query = $this->conn->prepare($sql_estados_ot);
            $query->execute();
            $estados_ot = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // OT Atrasadas
            $sql_ot_atrasadas = "SELECT COUNT(*) as cantidad
                                 FROM orden_trabajos ot
                                 WHERE fecha_entrega IS NOT NULL 
                                 AND fecha_entrega < CURDATE()
                                 AND (estado_ing != 1 OR estado_ing IS NULL)" . ($proyecto > 0 ? " AND ot.codigo = " . intval($proyecto) : "");
            $query = $this->conn->prepare($sql_ot_atrasadas);
            $query->execute();
            $ot_atrasadas = $query->fetch(PDO::FETCH_ASSOC)['cantidad'];
            
            // Proyectos más activos (OT con más subidas + descargas)
            $sql_proyectos_activos = "SELECT 
                                        ot.codigo,
                                        ot.nro_serie,
                                        ot.cliente,
                                        (SELECT COUNT(DISTINCT a.codigo) 
                                         FROM orden_trabajos_archivos ota
                                         INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                         WHERE (ota.ot_id = ot.codigo 
                                                OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                                OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                             INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                             WHERE otd.orden_trabajo_id = ot.codigo))
                                         AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as uploads,
                                        (SELECT COUNT(*) FROM orden_trabajos_eventos ote
                                         INNER JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                         WHERE otd.orden_trabajo_id = ot.codigo 
                                         AND ote.evento_id in (13, 5) 
                                         AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as downloads
                                      FROM orden_trabajos ot
                                      WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'" . ($proyecto > 0 ? " AND ot.codigo = " . intval($proyecto) : "") . "
                                      HAVING (uploads + downloads) > 0
                                      ORDER BY (uploads + downloads) DESC
                                      LIMIT 10";
            $query = $this->conn->prepare($sql_proyectos_activos);
            $query->execute();
            $proyectos_activos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Usuarios sin actividad
            $sql_usuarios_inactivos = "SELECT u.usuario, u.nombre
                                       FROM usuarios u
                                       WHERE u.fecha_baja IS NULL
                                       AND NOT EXISTS (
                                           SELECT 1 FROM orden_trabajos_archivos ota
                                           INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                           WHERE a.usuario_m = u.usuario 
                                           AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                       )
                                       AND NOT EXISTS (
                                           SELECT 1 FROM orden_trabajos_eventos ote
                                           WHERE ote.usuario_m = u.usuario
                                           AND ote.evento_id in (13, 5)
                                           AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                       )
                                       LIMIT 10";
            $query = $this->conn->prepare($sql_usuarios_inactivos);
            $query->execute();
            $usuarios_sin_actividad = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Proyectos sin movimientos
            $sql_proyectos_sin_mov = "SELECT ot.codigo, ot.nro_serie, ot.cliente
                                      FROM orden_trabajos ot
                                      WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'" . ($proyecto > 0 ? " AND ot.codigo = " . intval($proyecto) : "") . "
                                      AND NOT EXISTS (
                                          SELECT 1 FROM orden_trabajos_archivos ota
                                          INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                          WHERE (ota.ot_id = ot.codigo 
                                                 OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                                 OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                              INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                              WHERE otd.orden_trabajo_id = ot.codigo))
                                          AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                      )
                                      AND NOT EXISTS (
                                          SELECT 1 FROM orden_trabajos_eventos ote
                                          INNER JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                          WHERE otd.orden_trabajo_id = ot.codigo
                                          AND ote.evento_id in (13, 5)
                                          AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                      )
                                      LIMIT 10";
            $query = $this->conn->prepare($sql_proyectos_sin_mov);
            $query->execute();
            $proyectos_sin_mov = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return array(
                'kpis' => array(
                    'archivos_subidos' => $uploads_actual,
                    'variacion_uploads' => $variacion_uploads,
                    'descargas' => $downloads_actual,
                    'variacion_downloads' => $variacion_downloads,
                    'total_ot' => $ots_actual,
                    'variacion_ot' => $variacion_ots,
                    'usuarios_activos' => $usuarios_activos,
                    'usuarios_inactivos' => $usuarios_inactivos
                ),
                'actividad' => array(
                    'uploads' => $actividad_uploads,
                    'downloads' => $actividad_downloads
                ),
                'top_uploads' => $top_uploads,
                'top_downloads' => $top_downloads,
                'estados_ot' => $estados_ot,
                'proyectos_activos' => $proyectos_activos,
                'alertas' => array(
                    'ot_atrasadas' => $ot_atrasadas,
                    'usuarios_sin_actividad' => $usuarios_sin_actividad,
                    'proyectos_sin_mov' => $proyectos_sin_mov
                )
            );
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    public function getClientes(){
        try {
            $sql = "SELECT DISTINCT ot.codigo, ot.cliente, ot.nro_serie 
                    FROM orden_trabajos ot 
                    ORDER BY ot.cliente";
            $query = $this->conn->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }
    
    public function getUsuarios(){
        try {
            $sql = "SELECT DISTINCT usuario_m as usuario 
                    FROM archivos 
                    WHERE usuario_m IS NOT NULL AND usuario_m != ''
                    UNION
                    SELECT DISTINCT usuario_m as usuario 
                    FROM orden_trabajos_eventos 
                    WHERE usuario_m IS NOT NULL AND usuario_m != ''
                    ORDER BY usuario";
            $query = $this->conn->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }
}

?>
