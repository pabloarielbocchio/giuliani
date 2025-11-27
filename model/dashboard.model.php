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
    
    public function getActividadSubidas($fecha_desde, $fecha_hasta, $usuario, $proyecto, $tipo_archivo, $granularidad){
        try {
            // Construir filtros
            $filtro_usuario = $usuario != '0' ? " AND a.usuario_m = '" . addslashes($usuario) . "'" : "";
            $filtro_tipo = $tipo_archivo != '' ? " AND LOWER(SUBSTRING_INDEX(a.ruta, '.', -1)) = '" . strtolower($tipo_archivo) . "'" : "";
            
            // Calcular período anterior para comparación
            $fecha_desde_obj = new DateTime($fecha_desde);
            $fecha_hasta_obj = new DateTime($fecha_hasta);
            $diferencia = $fecha_desde_obj->diff($fecha_hasta_obj)->days;
            $fecha_desde_anterior = date('Y-m-d', strtotime($fecha_desde . ' -' . ($diferencia + 1) . ' days'));
            $fecha_hasta_anterior = date('Y-m-d', strtotime($fecha_desde . ' -1 day'));
            
            // KPIs - Total Subidas
            $sql_total = "SELECT COUNT(DISTINCT a.codigo) as total 
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
                              OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo;
            $query = $this->conn->prepare($sql_total);
            $query->execute();
            $total_actual = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sql_total_anterior = "SELECT COUNT(DISTINCT a.codigo) as total 
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
                                       OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo;
            $query = $this->conn->prepare($sql_total_anterior);
            $query->execute();
            $total_anterior = $query->fetch(PDO::FETCH_ASSOC)['total'];
            $variacion_total = $total_anterior > 0 ? (($total_actual - $total_anterior) / $total_anterior) * 100 : 0;
            
            // KPI - Promedio por día
            $dias_periodo = $diferencia + 1;
            $promedio_dia = $dias_periodo > 0 ? $total_actual / $dias_periodo : 0;
            
            // KPI - Usuarios que subieron
            $sql_usuarios = "SELECT COUNT(DISTINCT a.usuario_m) as total 
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
                                 OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo;
            $query = $this->conn->prepare($sql_usuarios);
            $query->execute();
            $usuarios_activos = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Evolución temporal según granularidad
            $group_by = "";
            $date_format = "";
            switch($granularidad) {
                case 'semana':
                    $group_by = "YEARWEEK(a.fecha_hora)";
                    $date_format = "CONCAT(YEAR(a.fecha_hora), '-W', LPAD(WEEK(a.fecha_hora), 2, '0'))";
                    break;
                case 'mes':
                    $group_by = "YEAR(a.fecha_hora), MONTH(a.fecha_hora)";
                    $date_format = "DATE_FORMAT(a.fecha_hora, '%Y-%m')";
                    break;
                default: // dia
                    $group_by = "DATE(a.fecha_hora)";
                    $date_format = "DATE(a.fecha_hora)";
            }
            
            $sql_evolucion = "SELECT 
                                " . $date_format . " as fecha,
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
                                   OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo . "
                              GROUP BY " . $group_by . "
                              ORDER BY fecha";
            $query = $this->conn->prepare($sql_evolucion);
            $query->execute();
            $evolucion = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 10 Usuarios
            $sql_top_usuarios = "SELECT 
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
                                     OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo . "
                                GROUP BY a.usuario_m
                                ORDER BY cantidad DESC
                                LIMIT 10";
            $query = $this->conn->prepare($sql_top_usuarios);
            $query->execute();
            $top_usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Heatmap - Horas pico
            $sql_heatmap = "SELECT 
                              HOUR(a.fecha_hora) as hora,
                              DAYOFWEEK(a.fecha_hora) as dia_semana,
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
                                 OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo . "
                            GROUP BY HOUR(a.fecha_hora), DAYOFWEEK(a.fecha_hora)
                            ORDER BY dia_semana, hora";
            $query = $this->conn->prepare($sql_heatmap);
            $query->execute();
            $heatmap = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Subidas por Proyecto/OT
            $sql_proyectos = "SELECT 
                                COALESCE(ot1.codigo, ot2.codigo, ot3.codigo) as ot_codigo,
                                COALESCE(ot1.nro_serie, ot2.nro_serie, ot3.nro_serie) as nro_serie,
                                COALESCE(ot1.cliente, ot2.cliente, ot3.cliente) as cliente,
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
                                   OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo . "
                              GROUP BY ot_codigo, nro_serie, cliente
                              ORDER BY cantidad DESC
                              LIMIT 20";
            $query = $this->conn->prepare($sql_proyectos);
            $query->execute();
            $subidas_proyectos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Subidas por Tipo de Archivo
            $sql_tipos = "SELECT 
                            UPPER(SUBSTRING_INDEX(a.ruta, '.', -1)) as tipo,
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
                               OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo . "
                          GROUP BY tipo
                          ORDER BY cantidad DESC";
            $query = $this->conn->prepare($sql_tipos);
            $query->execute();
            $subidas_tipos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Tabla Detallada
            $sql_detalle = "SELECT 
                              a.codigo,
                              a.fecha_hora,
                              a.usuario_m as usuario,
                              COALESCE(ot1.cliente, ot2.cliente, ot3.cliente) as cliente,
                              COALESCE(ot1.nro_serie, ot2.nro_serie, ot3.nro_serie) as nro_serie,
                              UPPER(SUBSTRING_INDEX(a.ruta, '.', -1)) as tipo_archivo,
                              a.descripcion as nombre_archivo,
                              a.ruta
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
                                 OR ot3.codigo " . ($proyecto > 0 ? "= " . intval($proyecto) : "IS NOT NULL") . ")" . $filtro_usuario . $filtro_tipo . "
                            ORDER BY a.fecha_hora DESC
                            LIMIT 500";
            $query = $this->conn->prepare($sql_detalle);
            $query->execute();
            $tabla_detalle = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return array(
                'kpis' => array(
                    'total_subidas' => $total_actual,
                    'variacion_total' => $variacion_total,
                    'promedio_dia' => $promedio_dia,
                    'usuarios_activos' => $usuarios_activos
                ),
                'evolucion' => $evolucion,
                'top_usuarios' => $top_usuarios,
                'heatmap' => $heatmap,
                'subidas_proyectos' => $subidas_proyectos,
                'subidas_tipos' => $subidas_tipos,
                'tabla_detalle' => $tabla_detalle,
                'granularidad' => $granularidad
            );
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    public function getActividadDescargas($fecha_desde, $fecha_hasta, $granularidad){
        try {
            // Calcular período anterior para comparación
            $fecha_desde_obj = new DateTime($fecha_desde);
            $fecha_hasta_obj = new DateTime($fecha_hasta);
            $diferencia = $fecha_desde_obj->diff($fecha_hasta_obj)->days;
            $fecha_desde_anterior = date('Y-m-d', strtotime($fecha_desde . ' -' . ($diferencia + 1) . ' days'));
            $fecha_hasta_anterior = date('Y-m-d', strtotime($fecha_desde . ' -1 day'));
            
            // KPIs - Total Descargas
            $sql_total = "SELECT COUNT(*) as total 
                         FROM orden_trabajos_eventos ote
                         LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                         LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                         WHERE ote.evento_id IN (13, 5)
                         AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
            $query = $this->conn->prepare($sql_total);
            $query->execute();
            $total_actual = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            $sql_total_anterior = "SELECT COUNT(*) as total 
                                  FROM orden_trabajos_eventos ote
                                  LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                  LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                                  WHERE ote.evento_id IN (13, 5)
                                  AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde_anterior . "' AND '" . $fecha_hasta_anterior . "'";
            $query = $this->conn->prepare($sql_total_anterior);
            $query->execute();
            $total_anterior = $query->fetch(PDO::FETCH_ASSOC)['total'];
            $variacion_total = $total_anterior > 0 ? (($total_actual - $total_anterior) / $total_anterior) * 100 : 0;
            
            // KPI - Promedio por día
            $dias_periodo = $diferencia + 1;
            $promedio_dia = $dias_periodo > 0 ? $total_actual / $dias_periodo : 0;
            
            // KPI - Usuarios que descargaron
            $sql_usuarios = "SELECT COUNT(DISTINCT ote.usuario_m) as total 
                            FROM orden_trabajos_eventos ote
                            LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                            LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                            WHERE ote.evento_id IN (13, 5)
                            AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
            $query = $this->conn->prepare($sql_usuarios);
            $query->execute();
            $usuarios_activos = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Evolución temporal según granularidad
            $group_by = "";
            $date_format = "";
            switch($granularidad) {
                case 'semana':
                    $group_by = "YEARWEEK(ote.fecha_m)";
                    $date_format = "CONCAT(YEAR(ote.fecha_m), '-W', LPAD(WEEK(ote.fecha_m), 2, '0'))";
                    break;
                case 'mes':
                    $group_by = "YEAR(ote.fecha_m), MONTH(ote.fecha_m)";
                    $date_format = "DATE_FORMAT(ote.fecha_m, '%Y-%m')";
                    break;
                default: // dia
                    $group_by = "DATE(ote.fecha_m)";
                    $date_format = "DATE(ote.fecha_m)";
            }
            
            $sql_evolucion = "SELECT 
                                " . $date_format . " as fecha,
                                COUNT(*) as cantidad
                              FROM orden_trabajos_eventos ote
                              LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                              LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                              WHERE ote.evento_id IN (13, 5)
                              AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                              GROUP BY " . $group_by . "
                              ORDER BY fecha";
            $query = $this->conn->prepare($sql_evolucion);
            $query->execute();
            $evolucion = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 10 Usuarios
            $sql_top_usuarios = "SELECT 
                                  ote.usuario_m as usuario,
                                  COUNT(*) as cantidad
                                FROM orden_trabajos_eventos ote
                                LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                                WHERE ote.evento_id IN (13, 5)
                                AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                GROUP BY ote.usuario_m
                                ORDER BY cantidad DESC
                                LIMIT 10";
            $query = $this->conn->prepare($sql_top_usuarios);
            $query->execute();
            $top_usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 10 Archivos más descargados
            $sql_top_archivos = "SELECT 
                                  a.codigo,
                                  a.descripcion as nombre_archivo,
                                  a.ruta,
                                  UPPER(SUBSTRING_INDEX(a.ruta, '.', -1)) as tipo_archivo,
                                  COALESCE(ot.cliente, 'Sin Proyecto') as cliente,
                                  COALESCE(ot.nro_serie, '-') as nro_serie,
                                  COUNT(*) as cantidad,
                                  MAX(ote.fecha_m) as ultima_descarga
                                FROM orden_trabajos_eventos ote
                                LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                                LEFT JOIN orden_trabajos_archivos ota ON (ota.ot_detalle_id = otd.codigo OR ota.ot_produccion_id IN (SELECT codigo FROM orden_trabajos_produccion WHERE ot_detalle_id = otd.codigo))
                                LEFT JOIN archivos a ON ota.archivo_id = a.codigo
                                WHERE ote.evento_id IN (13, 5)
                                AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                AND a.codigo IS NOT NULL
                                GROUP BY a.codigo, a.descripcion, a.ruta, tipo_archivo, ot.cliente, ot.nro_serie
                                ORDER BY cantidad DESC
                                LIMIT 10";
            $query = $this->conn->prepare($sql_top_archivos);
            $query->execute();
            $top_archivos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Descargas por Proyecto/OT
            $sql_proyectos = "SELECT 
                                ot.codigo as ot_codigo,
                                ot.nro_serie,
                                ot.cliente,
                                COUNT(*) as cantidad
                              FROM orden_trabajos_eventos ote
                              LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                              LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                              WHERE ote.evento_id IN (13, 5)
                              AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                              AND ot.codigo IS NOT NULL
                              GROUP BY ot.codigo, ot.nro_serie, ot.cliente
                              ORDER BY cantidad DESC
                              LIMIT 20";
            $query = $this->conn->prepare($sql_proyectos);
            $query->execute();
            $descargas_proyectos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Heatmap - Horas pico
            $sql_heatmap = "SELECT 
                              HOUR(ote.fecha_m) as hora,
                              DAYOFWEEK(ote.fecha_m) as dia_semana,
                              COUNT(*) as cantidad
                            FROM orden_trabajos_eventos ote
                            LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                            LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                            WHERE ote.evento_id IN (13, 5)
                            AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            GROUP BY HOUR(ote.fecha_m), DAYOFWEEK(ote.fecha_m)
                            ORDER BY dia_semana, hora";
            $query = $this->conn->prepare($sql_heatmap);
            $query->execute();
            $heatmap = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Tabla Detallada
            $sql_detalle = "SELECT 
                              ote.codigo,
                              ote.fecha_m,
                              ote.usuario_m as usuario,
                              COALESCE(ot.cliente, 'Sin Proyecto') as cliente,
                              COALESCE(ot.nro_serie, '-') as nro_serie,
                              a.descripcion as nombre_archivo,
                              UPPER(SUBSTRING_INDEX(a.ruta, '.', -1)) as tipo_archivo
                            FROM orden_trabajos_eventos ote
                            LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                            LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                            LEFT JOIN orden_trabajos_archivos ota ON (ota.ot_detalle_id = otd.codigo OR ota.ot_produccion_id IN (SELECT codigo FROM orden_trabajos_produccion WHERE ot_detalle_id = otd.codigo))
                            LEFT JOIN archivos a ON ota.archivo_id = a.codigo
                            WHERE ote.evento_id IN (13, 5)
                            AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            ORDER BY ote.fecha_m DESC
                            LIMIT 500";
            $query = $this->conn->prepare($sql_detalle);
            $query->execute();
            $tabla_detalle = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return array(
                'kpis' => array(
                    'total_descargas' => $total_actual,
                    'variacion_total' => $variacion_total,
                    'promedio_dia' => $promedio_dia,
                    'usuarios_activos' => $usuarios_activos
                ),
                'evolucion' => $evolucion,
                'top_usuarios' => $top_usuarios,
                'top_archivos' => $top_archivos,
                'descargas_proyectos' => $descargas_proyectos,
                'heatmap' => $heatmap,
                'tabla_detalle' => $tabla_detalle,
                'granularidad' => $granularidad
            );
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    public function getDashboardUsuarios($fecha_desde, $fecha_hasta, $granularidad){
        try {
            // KPIs - Usuarios Totales
            $sql_total = "SELECT COUNT(*) as total FROM usuarios WHERE fecha_baja IS NULL";
            $query = $this->conn->prepare($sql_total);
            $query->execute();
            $usuarios_totales = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // KPIs - Usuarios Activos (últimos 30 días)
            $sql_activos = "SELECT COUNT(DISTINCT usuario) as total
                           FROM (
                               SELECT DISTINCT a.usuario_m as usuario
                               FROM orden_trabajos_archivos ota
                               INNER JOIN archivos a ON a.codigo = ota.archivo_id
                               WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                               UNION
                               SELECT DISTINCT ote.usuario_m as usuario
                               FROM orden_trabajos_eventos ote
                               WHERE ote.evento_id IN (13, 5)
                               AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                           ) as usuarios_activos";
            $query = $this->conn->prepare($sql_activos);
            $query->execute();
            $usuarios_activos = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // KPIs - Usuarios Inactivos
            $sql_inactivos = "SELECT COUNT(*) as total
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
                                 AND ote.evento_id IN (13, 5)
                                 AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                             )";
            $query = $this->conn->prepare($sql_inactivos);
            $query->execute();
            $usuarios_inactivos = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Evolución temporal según granularidad
            $group_by = "";
            $date_format = "";
            switch($granularidad) {
                case 'semana':
                    $group_by = "YEARWEEK(fecha_actividad)";
                    $date_format = "CONCAT(YEAR(fecha_actividad), '-W', LPAD(WEEK(fecha_actividad), 2, '0'))";
                    break;
                case 'mes':
                    $group_by = "YEAR(fecha_actividad), MONTH(fecha_actividad)";
                    $date_format = "DATE_FORMAT(fecha_actividad, '%Y-%m')";
                    break;
                default: // dia
                    $group_by = "DATE(fecha_actividad)";
                    $date_format = "DATE(fecha_actividad)";
            }
            
            $sql_evolucion = "SELECT 
                                " . $date_format . " as fecha,
                                COUNT(DISTINCT usuario) as cantidad
                              FROM (
                                  SELECT DATE(a.fecha_hora) as fecha_actividad, a.usuario_m as usuario
                                  FROM orden_trabajos_archivos ota
                                  INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                  WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                  UNION
                                  SELECT DATE(ote.fecha_m) as fecha_actividad, ote.usuario_m as usuario
                                  FROM orden_trabajos_eventos ote
                                  WHERE ote.evento_id IN (13, 5)
                                  AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                              ) as actividad
                              GROUP BY " . $group_by . "
                              ORDER BY fecha";
            $query = $this->conn->prepare($sql_evolucion);
            $query->execute();
            $evolucion = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 10 Usuarios que Más Suben
            $sql_top_uploads = "SELECT 
                                  a.usuario_m as usuario,
                                  COUNT(DISTINCT a.codigo) as cantidad
                                FROM orden_trabajos_archivos ota
                                INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                GROUP BY a.usuario_m
                                ORDER BY cantidad DESC
                                LIMIT 10";
            $query = $this->conn->prepare($sql_top_uploads);
            $query->execute();
            $top_uploads = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Top 10 Usuarios que Más Descargan
            $sql_top_downloads = "SELECT 
                                   ote.usuario_m as usuario,
                                   COUNT(*) as cantidad
                                 FROM orden_trabajos_eventos ote
                                 WHERE ote.evento_id IN (13, 5)
                                 AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                 GROUP BY ote.usuario_m
                                 ORDER BY cantidad DESC
                                 LIMIT 10";
            $query = $this->conn->prepare($sql_top_downloads);
            $query->execute();
            $top_downloads = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Última Actividad por Usuario
            $sql_ultima_actividad = "SELECT 
                                       u.usuario,
                                       u.nombre,
                                       GREATEST(
                                           COALESCE((SELECT MAX(a.fecha_hora) FROM orden_trabajos_archivos ota
                                                     INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                                     WHERE a.usuario_m = u.usuario), '1900-01-01'),
                                           COALESCE((SELECT MAX(ote.fecha_m) FROM orden_trabajos_eventos ote
                                                     WHERE ote.usuario_m = u.usuario
                                                     AND ote.evento_id IN (13, 5)), '1900-01-01')
                                       ) as ultima_actividad
                                     FROM usuarios u
                                     WHERE u.fecha_baja IS NULL
                                     ORDER BY ultima_actividad DESC
                                     LIMIT 50";
            $query = $this->conn->prepare($sql_ultima_actividad);
            $query->execute();
            $ultima_actividad = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Usuarios por Rol
            $sql_por_rol = "SELECT 
                              COALESCE(r.descripcion, 'Sin Rol') as rol,
                              COUNT(*) as cantidad
                            FROM usuarios u
                            LEFT JOIN roles r ON u.id_rol = r.codigo
                            WHERE u.fecha_baja IS NULL
                            GROUP BY r.codigo, r.descripcion
                            ORDER BY cantidad DESC";
            $query = $this->conn->prepare($sql_por_rol);
            $query->execute();
            $usuarios_por_rol = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Usuarios Inactivos (últimos 30 días)
            $sql_inactivos_detalle = "SELECT 
                                        u.usuario,
                                        u.nombre,
                                        GREATEST(
                                            COALESCE((SELECT MAX(a.fecha_hora) FROM orden_trabajos_archivos ota
                                                      INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                                      WHERE a.usuario_m = u.usuario), '1900-01-01'),
                                            COALESCE((SELECT MAX(ote.fecha_m) FROM orden_trabajos_eventos ote
                                                      WHERE ote.usuario_m = u.usuario
                                                      AND ote.evento_id IN (13, 5)), '1900-01-01')
                                        ) as ultima_actividad,
                                        DATEDIFF(CURDATE(), GREATEST(
                                            COALESCE((SELECT MAX(a.fecha_hora) FROM orden_trabajos_archivos ota
                                                      INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                                      WHERE a.usuario_m = u.usuario), '1900-01-01'),
                                            COALESCE((SELECT MAX(ote.fecha_m) FROM orden_trabajos_eventos ote
                                                      WHERE ote.usuario_m = u.usuario
                                                      AND ote.evento_id IN (13, 5)), '1900-01-01')
                                        )) as dias_sin_actividad,
                                        (SELECT COUNT(DISTINCT a.codigo) FROM orden_trabajos_archivos ota
                                         INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                         WHERE a.usuario_m = u.usuario
                                         AND DATE(a.fecha_hora) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)) as uploads_ultimo_mes,
                                        (SELECT COUNT(*) FROM orden_trabajos_eventos ote
                                         WHERE ote.usuario_m = u.usuario
                                         AND ote.evento_id IN (13, 5)
                                         AND DATE(ote.fecha_m) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)) as downloads_ultimo_mes
                                      FROM usuarios u
                                      WHERE u.fecha_baja IS NULL
                                      AND (
                                          NOT EXISTS (
                                              SELECT 1 FROM orden_trabajos_archivos ota
                                              INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                              WHERE a.usuario_m = u.usuario
                                              AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                          )
                                          AND NOT EXISTS (
                                              SELECT 1 FROM orden_trabajos_eventos ote
                                              WHERE ote.usuario_m = u.usuario
                                              AND ote.evento_id IN (13, 5)
                                              AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                          )
                                      )
                                      ORDER BY dias_sin_actividad DESC
                                      LIMIT 50";
            $query = $this->conn->prepare($sql_inactivos_detalle);
            $query->execute();
            $usuarios_inactivos_detalle = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Perfil de Comportamiento (Funnel)
            $sql_funnel = "SELECT 
                             u.usuario,
                             u.nombre,
                             (SELECT COUNT(DISTINCT a.codigo) FROM orden_trabajos_archivos ota
                              INNER JOIN archivos a ON a.codigo = ota.archivo_id
                              WHERE a.usuario_m = u.usuario
                              AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as subidas,
                             (SELECT COUNT(*) FROM orden_trabajos_eventos ote
                              WHERE ote.usuario_m = u.usuario
                              AND ote.evento_id IN (13, 5)
                              AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as descargas,
                             (SELECT COUNT(DISTINCT ot.codigo) FROM orden_trabajos ot
                              WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                              AND EXISTS (
                                  SELECT 1 FROM orden_trabajos_archivos ota
                                  INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                  WHERE a.usuario_m = u.usuario
                                  AND (ota.ot_id = ot.codigo OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo))
                              )) as ot_gestionadas,
                             (SELECT COUNT(DISTINCT ot.codigo) FROM orden_trabajos ot
                              WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                              AND ot.estado_ing = 1
                              AND EXISTS (
                                  SELECT 1 FROM orden_trabajos_archivos ota
                                  INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                  WHERE a.usuario_m = u.usuario
                                  AND (ota.ot_id = ot.codigo OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo))
                              )) as ot_cerradas
                           FROM usuarios u
                           WHERE u.fecha_baja IS NULL
                           AND (
                               EXISTS (
                                   SELECT 1 FROM orden_trabajos_archivos ota
                                   INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                   WHERE a.usuario_m = u.usuario
                                   AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                               )
                               OR EXISTS (
                                   SELECT 1 FROM orden_trabajos_eventos ote
                                   WHERE ote.usuario_m = u.usuario
                                   AND ote.evento_id IN (13, 5)
                                   AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                               )
                           )
                           ORDER BY (subidas + descargas) DESC
                           LIMIT 50";
            $query = $this->conn->prepare($sql_funnel);
            $query->execute();
            $funnel = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Registro Detallado por Usuario
            $sql_detalle = "SELECT 
                              'Subida' as tipo_accion,
                              a.fecha_hora as fecha_hora,
                              a.usuario_m as usuario,
                              COALESCE(ot1.cliente, ot2.cliente, ot3.cliente, 'Sin Proyecto') as cliente,
                              COALESCE(ot1.nro_serie, ot2.nro_serie, ot3.nro_serie, '-') as nro_serie,
                              a.descripcion as archivo,
                              UPPER(SUBSTRING_INDEX(a.ruta, '.', -1)) as tipo_archivo
                            FROM orden_trabajos_archivos ota
                            INNER JOIN archivos a ON a.codigo = ota.archivo_id
                            LEFT JOIN orden_trabajos ot1 ON ota.ot_id = ot1.codigo
                            LEFT JOIN orden_trabajos_detalles otd ON ota.ot_detalle_id = otd.codigo
                            LEFT JOIN orden_trabajos ot2 ON otd.orden_trabajo_id = ot2.codigo
                            LEFT JOIN orden_trabajos_produccion otp ON ota.ot_produccion_id = otp.codigo
                            LEFT JOIN orden_trabajos_detalles otd2 ON otp.ot_detalle_id = otd2.codigo
                            LEFT JOIN orden_trabajos ot3 ON otd2.orden_trabajo_id = ot3.codigo
                            WHERE DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            UNION ALL
                            SELECT 
                              'Descarga' as tipo_accion,
                              ote.fecha_m as fecha_hora,
                              ote.usuario_m as usuario,
                              COALESCE(ot.cliente, 'Sin Proyecto') as cliente,
                              COALESCE(ot.nro_serie, '-') as nro_serie,
                              COALESCE(a.descripcion, 'Archivo descargado') as archivo,
                              COALESCE(UPPER(SUBSTRING_INDEX(a.ruta, '.', -1)), '-') as tipo_archivo
                            FROM orden_trabajos_eventos ote
                            LEFT JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                            LEFT JOIN orden_trabajos ot ON otd.orden_trabajo_id = ot.codigo
                            LEFT JOIN orden_trabajos_archivos ota ON (ota.ot_detalle_id = otd.codigo OR ota.ot_produccion_id IN (SELECT codigo FROM orden_trabajos_produccion WHERE ot_detalle_id = otd.codigo))
                            LEFT JOIN archivos a ON ota.archivo_id = a.codigo
                            WHERE ote.evento_id IN (13, 5)
                            AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            ORDER BY fecha_hora DESC
                            LIMIT 500";
            $query = $this->conn->prepare($sql_detalle);
            $query->execute();
            $tabla_detalle = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return array(
                'kpis' => array(
                    'usuarios_totales' => $usuarios_totales,
                    'usuarios_activos' => $usuarios_activos,
                    'usuarios_inactivos' => $usuarios_inactivos
                ),
                'evolucion' => $evolucion,
                'top_uploads' => $top_uploads,
                'top_downloads' => $top_downloads,
                'ultima_actividad' => $ultima_actividad,
                'usuarios_por_rol' => $usuarios_por_rol,
                'usuarios_inactivos_detalle' => $usuarios_inactivos_detalle,
                'funnel' => $funnel,
                'tabla_detalle' => $tabla_detalle,
                'granularidad' => $granularidad
            );
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    public function getOrdenesTrabajo($fecha_desde, $fecha_hasta, $granularidad){
        try {
            // KPIs - Total OT
            $sql_total = "SELECT COUNT(*) as total 
                         FROM orden_trabajos ot
                         WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'";
            $query = $this->conn->prepare($sql_total);
            $query->execute();
            $total_ot = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // KPIs - OT Activas (no cerradas)
            $sql_activas = "SELECT COUNT(DISTINCT ot.codigo) as total
                           FROM orden_trabajos ot
                           LEFT JOIN orden_trabajos_detalles otd ON ot.codigo = otd.orden_trabajo_id
                           LEFT JOIN orden_trabajos_produccion otp ON otd.codigo = otp.ot_detalle_id
                           LEFT JOIN orden_trabajos_estados ote ON otp.codigo = ote.ot_prod_id
                           LEFT JOIN estados e ON ote.estado_id = e.codigo
                           WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                           AND (ot.estado_ing != 1 OR ot.estado_ing IS NULL)
                           AND (e.descripcion NOT LIKE '%Cerrada%' OR e.descripcion IS NULL)";
            $query = $this->conn->prepare($sql_activas);
            $query->execute();
            $ot_activas = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // KPIs - OT Atrasadas
            $sql_atrasadas = "SELECT COUNT(DISTINCT ot.codigo) as total
                             FROM orden_trabajos ot
                             WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                             AND ot.fecha_entrega IS NOT NULL 
                             AND DATE(ot.fecha_entrega) < CURDATE()
                             AND (ot.estado_ing != 1 OR ot.estado_ing IS NULL)";
            $query = $this->conn->prepare($sql_atrasadas);
            $query->execute();
            $ot_atrasadas = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // OT por Estado - Primero obtener estados desde orden_trabajos_estados
            $sql_estados = "SELECT 
                              COALESCE(e.descripcion, e.abrev, 'Sin Estado') as estado,
                              COUNT(DISTINCT ot.codigo) as cantidad
                            FROM orden_trabajos ot
                            INNER JOIN orden_trabajos_detalles otd ON ot.codigo = otd.orden_trabajo_id
                            INNER JOIN orden_trabajos_produccion otp ON otd.codigo = otp.ot_detalle_id
                            INNER JOIN orden_trabajos_estados ote ON otp.codigo = ote.ot_prod_id
                            INNER JOIN estados e ON ote.estado_id = e.codigo
                            WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            GROUP BY e.codigo, e.descripcion, e.abrev
                            UNION ALL
                            SELECT 
                              CASE WHEN ot.estado_ing = 1 THEN 'Cerrada' ELSE 'Sin Estado Asignado' END as estado,
                              COUNT(DISTINCT ot.codigo) as cantidad
                            FROM orden_trabajos ot
                            WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            AND NOT EXISTS (
                                SELECT 1 FROM orden_trabajos_detalles otd 
                                INNER JOIN orden_trabajos_produccion otp ON otd.codigo = otp.ot_detalle_id
                                INNER JOIN orden_trabajos_estados ote ON otp.codigo = ote.ot_prod_id
                                WHERE otd.orden_trabajo_id = ot.codigo
                            )
                            GROUP BY estado
                            ORDER BY cantidad DESC";
            $query = $this->conn->prepare($sql_estados);
            $query->execute();
            $ot_por_estado = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Evolución temporal según granularidad
            $group_by = "";
            $date_format = "";
            switch($granularidad) {
                case 'semana':
                    $group_by = "YEARWEEK(ot.fecha)";
                    $date_format = "CONCAT(YEAR(ot.fecha), '-W', LPAD(WEEK(ot.fecha), 2, '0'))";
                    break;
                case 'mes':
                    $group_by = "YEAR(ot.fecha), MONTH(ot.fecha)";
                    $date_format = "DATE_FORMAT(ot.fecha, '%Y-%m')";
                    break;
                default: // dia
                    $group_by = "DATE(ot.fecha)";
                    $date_format = "DATE(ot.fecha)";
            }
            
            // Evolución - OT Creadas
            $sql_creadas = "SELECT 
                             " . $date_format . " as fecha,
                             COUNT(*) as cantidad
                           FROM orden_trabajos ot
                           WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                           GROUP BY " . $group_by . "
                           ORDER BY fecha";
            $query = $this->conn->prepare($sql_creadas);
            $query->execute();
            $evolucion_creadas = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Evolución - OT Cerradas
            $sql_cerradas = "SELECT 
                              " . $date_format . " as fecha,
                              COUNT(DISTINCT ot.codigo) as cantidad
                            FROM orden_trabajos ot
                            WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            AND ot.estado_ing = 1
                            GROUP BY " . $group_by . "
                            ORDER BY fecha";
            $query = $this->conn->prepare($sql_cerradas);
            $query->execute();
            $evolucion_cerradas = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // OT por Responsable (usuario que creó o modificó)
            $sql_responsable = "SELECT 
                                  ot.usuario_m as responsable,
                                  COUNT(DISTINCT ot.codigo) as cantidad
                                FROM orden_trabajos ot
                                WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                AND ot.usuario_m IS NOT NULL
                                GROUP BY ot.usuario_m
                                ORDER BY cantidad DESC
                                LIMIT 20";
            $query = $this->conn->prepare($sql_responsable);
            $query->execute();
            $ot_por_responsable = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // OT por Proyecto
            $sql_proyecto = "SELECT 
                              ot.cliente as proyecto,
                              COUNT(DISTINCT ot.codigo) as cantidad
                            FROM orden_trabajos ot
                            WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            AND ot.cliente IS NOT NULL
                            GROUP BY ot.cliente
                            ORDER BY cantidad DESC
                            LIMIT 20";
            $query = $this->conn->prepare($sql_proyecto);
            $query->execute();
            $ot_por_proyecto = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Tiempo Promedio de Cierre
            $sql_tiempo_promedio = "SELECT 
                                      AVG(DATEDIFF(ot.fecha_m, ot.fecha)) as promedio_dias,
                                      MIN(DATEDIFF(ot.fecha_m, ot.fecha)) as minimo_dias,
                                      MAX(DATEDIFF(ot.fecha_m, ot.fecha)) as maximo_dias
                                    FROM orden_trabajos ot
                                    WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                    AND ot.estado_ing = 1
                                    AND ot.fecha_m IS NOT NULL";
            $query = $this->conn->prepare($sql_tiempo_promedio);
            $query->execute();
            $tiempo_promedio = $query->fetch(PDO::FETCH_ASSOC);
            
            // OT Atrasadas (Detalle)
            $sql_atrasadas_detalle = "SELECT 
                                       ot.codigo,
                                       ot.nro_serie,
                                       ot.cliente,
                                       ot.usuario_m as responsable,
                                       ot.fecha as fecha_creacion,
                                       ot.fecha_entrega,
                                       DATEDIFF(CURDATE(), ot.fecha) as dias_abiertos,
                                       DATEDIFF(CURDATE(), DATE(ot.fecha_entrega)) as dias_atrasados,
                                       GREATEST(
                                           COALESCE((SELECT MAX(a.fecha_hora) FROM orden_trabajos_archivos ota
                                                     INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                                     WHERE (ota.ot_id = ot.codigo OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)), '1900-01-01 00:00:00'),
                                           COALESCE((SELECT MAX(ote.fecha_m) FROM orden_trabajos_eventos ote
                                                     INNER JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                                     WHERE otd.orden_trabajo_id = ot.codigo), '1900-01-01 00:00:00')
                                       ) as ultima_actividad
                                     FROM orden_trabajos ot
                                     WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                     AND ot.fecha_entrega IS NOT NULL 
                                     AND DATE(ot.fecha_entrega) < CURDATE()
                                     AND (ot.estado_ing != 1 OR ot.estado_ing IS NULL)
                                     ORDER BY dias_atrasados DESC
                                     LIMIT 100";
            $query = $this->conn->prepare($sql_atrasadas_detalle);
            $query->execute();
            $ot_atrasadas_detalle = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Registro Completo de OT
            $sql_registro = "SELECT 
                              ot.codigo,
                              ot.nro_serie,
                              ot.cliente,
                              ot.usuario_m as responsable,
                              COALESCE(e.descripcion, e.abrev, 'Sin Estado') as estado,
                              ot.fecha as fecha_creacion,
                              CASE WHEN ot.estado_ing = 1 THEN ot.fecha_m ELSE NULL END as fecha_cierre,
                              DATEDIFF(COALESCE(ot.fecha_m, CURDATE()), ot.fecha) as tiempo_transcurrido
                            FROM orden_trabajos ot
                            LEFT JOIN orden_trabajos_detalles otd ON ot.codigo = otd.orden_trabajo_id
                            LEFT JOIN orden_trabajos_produccion otp ON otd.codigo = otp.ot_detalle_id
                            LEFT JOIN orden_trabajos_estados ote ON otp.codigo = ote.ot_prod_id
                            LEFT JOIN estados e ON ote.estado_id = e.codigo
                            WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                            ORDER BY ot.fecha DESC
                            LIMIT 500";
            $query = $this->conn->prepare($sql_registro);
            $query->execute();
            $registro_completo = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return array(
                'kpis' => array(
                    'total_ot' => $total_ot,
                    'ot_activas' => $ot_activas,
                    'ot_atrasadas' => $ot_atrasadas
                ),
                'ot_por_estado' => $ot_por_estado,
                'evolucion_creadas' => $evolucion_creadas,
                'evolucion_cerradas' => $evolucion_cerradas,
                'ot_por_responsable' => $ot_por_responsable,
                'ot_por_proyecto' => $ot_por_proyecto,
                'tiempo_promedio' => $tiempo_promedio,
                'ot_atrasadas_detalle' => $ot_atrasadas_detalle,
                'registro_completo' => $registro_completo,
                'granularidad' => $granularidad
            );
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
    
    public function getProyectos($fecha_desde, $fecha_hasta, $proyecto_seleccionado, $granularidad){
        try {
            // KPIs - Total Proyectos
            $sql_total = "SELECT COUNT(DISTINCT ot.cliente) as total 
                         FROM orden_trabajos ot
                         WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                         AND ot.cliente IS NOT NULL AND ot.cliente != ''";
            $query = $this->conn->prepare($sql_total);
            $query->execute();
            $total_proyectos = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // KPIs - Proyectos con OT
            $sql_con_ot = "SELECT COUNT(DISTINCT ot.cliente) as total 
                          FROM orden_trabajos ot
                          WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                          AND ot.cliente IS NOT NULL AND ot.cliente != ''";
            $query = $this->conn->prepare($sql_con_ot);
            $query->execute();
            $proyectos_con_ot = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // KPIs - Proyectos sin Actividad (últimos 30 días)
            $sql_sin_actividad = "SELECT COUNT(DISTINCT ot.cliente) as total
                                 FROM orden_trabajos ot
                                 WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                 AND ot.cliente IS NOT NULL AND ot.cliente != ''
                                 AND NOT EXISTS (
                                     SELECT 1 FROM orden_trabajos_archivos ota
                                     INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                     WHERE (ota.ot_id = ot.codigo 
                                            OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                            OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                         INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                         WHERE otd.orden_trabajo_id = ot.codigo))
                                     AND DATE(a.fecha_hora) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                 )
                                 AND NOT EXISTS (
                                     SELECT 1 FROM orden_trabajos_eventos ote
                                     INNER JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                     WHERE otd.orden_trabajo_id = ot.codigo
                                     AND ote.evento_id IN (13, 5)
                                     AND DATE(ote.fecha_m) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                 )";
            $query = $this->conn->prepare($sql_sin_actividad);
            $query->execute();
            $proyectos_sin_actividad = $query->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Subidas + Descargas por Proyecto
            $sql_actividad_proyectos = "SELECT 
                                          ot.cliente as proyecto,
                                          MIN(ot.codigo) as codigo_ot,
                                          (SELECT COUNT(DISTINCT a.codigo) 
                                           FROM orden_trabajos_archivos ota
                                           INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                           WHERE (ota.ot_id = ot.codigo 
                                                  OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                                  OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                               INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                               WHERE otd.orden_trabajo_id = ot.codigo))
                                           AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as subidas,
                                          (SELECT COUNT(*) 
                                           FROM orden_trabajos_eventos ote
                                           INNER JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                           WHERE otd.orden_trabajo_id = ot.codigo
                                           AND ote.evento_id IN (13, 5)
                                           AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as descargas
                                        FROM orden_trabajos ot
                                        WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                        AND ot.cliente IS NOT NULL AND ot.cliente != ''
                                        GROUP BY ot.cliente
                                        HAVING (subidas + descargas) > 0
                                        ORDER BY (subidas + descargas) DESC
                                        LIMIT 20";
            $query = $this->conn->prepare($sql_actividad_proyectos);
            $query->execute();
            $actividad_proyectos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Documentación por Proyecto (cantidad de archivos)
            $sql_documentacion = "SELECT 
                                   ot.cliente as proyecto,
                                   COUNT(DISTINCT a.codigo) as total_archivos
                                 FROM orden_trabajos ot
                                 LEFT JOIN orden_trabajos_archivos ota ON (ota.ot_id = ot.codigo 
                                                                          OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                                                          OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                                                       INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                                                       WHERE otd.orden_trabajo_id = ot.codigo))
                                 LEFT JOIN archivos a ON a.codigo = ota.archivo_id
                                 WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                 AND ot.cliente IS NOT NULL AND ot.cliente != ''
                                 GROUP BY ot.cliente
                                 ORDER BY total_archivos DESC
                                 LIMIT 20";
            $query = $this->conn->prepare($sql_documentacion);
            $query->execute();
            $documentacion_proyectos = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // OT por Proyecto y Estado
            $sql_ot_por_proyecto_estado = "SELECT 
                                             ot.cliente as proyecto,
                                             COALESCE(e.descripcion, e.abrev, CASE WHEN ot.estado_ing = 1 THEN 'Cerrada' ELSE 'Sin Estado' END) as estado,
                                             COUNT(DISTINCT ot.codigo) as cantidad
                                           FROM orden_trabajos ot
                                           LEFT JOIN orden_trabajos_detalles otd ON ot.codigo = otd.orden_trabajo_id
                                           LEFT JOIN orden_trabajos_produccion otp ON otd.codigo = otp.ot_detalle_id
                                           LEFT JOIN orden_trabajos_estados ote ON otp.codigo = ote.ot_prod_id
                                           LEFT JOIN estados e ON ote.estado_id = e.codigo
                                           WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                           AND ot.cliente IS NOT NULL AND ot.cliente != ''
                                           GROUP BY ot.cliente, estado
                                           ORDER BY ot.cliente, cantidad DESC";
            $query = $this->conn->prepare($sql_ot_por_proyecto_estado);
            $query->execute();
            $ot_por_proyecto_estado = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Tipos de Archivo por Proyecto
            $sql_tipos_proyecto = "SELECT 
                                    ot.cliente as proyecto,
                                    UPPER(SUBSTRING_INDEX(a.ruta, '.', -1)) as tipo,
                                    COUNT(DISTINCT a.codigo) as cantidad
                                  FROM orden_trabajos ot
                                  LEFT JOIN orden_trabajos_archivos ota ON (ota.ot_id = ot.codigo 
                                                                           OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                                                           OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                                                        INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                                                        WHERE otd.orden_trabajo_id = ot.codigo))
                                  LEFT JOIN archivos a ON a.codigo = ota.archivo_id
                                  WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                  AND ot.cliente IS NOT NULL AND ot.cliente != ''
                                  AND a.codigo IS NOT NULL
                                  GROUP BY ot.cliente, tipo
                                  ORDER BY ot.cliente, cantidad DESC";
            $query = $this->conn->prepare($sql_tipos_proyecto);
            $query->execute();
            $tipos_proyecto = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Actividad Temporal del Proyecto Seleccionado
            $actividad_temporal = array();
            if ($proyecto_seleccionado > 0) {
                $proyecto_nombre = '';
                // Obtener el nombre del proyecto
                $sql_proy_nombre = "SELECT DISTINCT cliente FROM orden_trabajos WHERE codigo = " . intval($proyecto_seleccionado) . " LIMIT 1";
                $query = $this->conn->prepare($sql_proy_nombre);
                $query->execute();
                $proy_result = $query->fetch(PDO::FETCH_ASSOC);
                if ($proy_result) {
                    $proyecto_nombre = $proy_result['cliente'];
                }
                
                if ($proyecto_nombre) {
                    $group_by = "";
                    $date_format = "";
                    switch($granularidad) {
                        case 'semana':
                            $group_by = "YEARWEEK(fecha_actividad)";
                            $date_format = "CONCAT(YEAR(fecha_actividad), '-W', LPAD(WEEK(fecha_actividad), 2, '0'))";
                            break;
                        case 'mes':
                            $group_by = "YEAR(fecha_actividad), MONTH(fecha_actividad)";
                            $date_format = "DATE_FORMAT(fecha_actividad, '%Y-%m')";
                            break;
                        default: // dia
                            $group_by = "DATE(fecha_actividad)";
                            $date_format = "DATE(fecha_actividad)";
                    }
                    
                    $sql_actividad_temporal = "SELECT 
                                                 " . $date_format . " as fecha,
                                                 'Subidas' as tipo,
                                                 COUNT(DISTINCT a.codigo) as cantidad
                                               FROM orden_trabajos ot
                                               LEFT JOIN orden_trabajos_archivos ota ON (ota.ot_id = ot.codigo 
                                                                                        OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                                                                        OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                                                                     INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                                                                     WHERE otd.orden_trabajo_id = ot.codigo))
                                               LEFT JOIN archivos a ON a.codigo = ota.archivo_id
                                               WHERE ot.cliente = '" . addslashes($proyecto_nombre) . "'
                                               AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                               GROUP BY " . $group_by . "
                                               UNION ALL
                                               SELECT 
                                                 " . $date_format . " as fecha,
                                                 'Descargas' as tipo,
                                                 COUNT(*) as cantidad
                                               FROM orden_trabajos ot
                                               LEFT JOIN orden_trabajos_detalles otd ON ot.codigo = otd.orden_trabajo_id
                                               LEFT JOIN orden_trabajos_eventos ote ON ote.ot_detalle_id = otd.codigo
                                               WHERE ot.cliente = '" . addslashes($proyecto_nombre) . "'
                                               AND ote.evento_id IN (13, 5)
                                               AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                               GROUP BY " . $group_by . "
                                               ORDER BY fecha, tipo";
                    $query = $this->conn->prepare($sql_actividad_temporal);
                    $query->execute();
                    $actividad_temporal = $query->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            
            // Cuadro Comparativo entre Proyectos
            $sql_comparativo = "SELECT 
                                 ot.cliente as proyecto,
                                 (SELECT COUNT(DISTINCT a.codigo) 
                                  FROM orden_trabajos_archivos ota
                                  INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                  WHERE (ota.ot_id = ot.codigo 
                                         OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                         OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                      INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                      WHERE otd.orden_trabajo_id = ot.codigo))
                                  AND DATE(a.fecha_hora) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as total_subidas,
                                 (SELECT COUNT(*) 
                                  FROM orden_trabajos_eventos ote
                                  INNER JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                  WHERE otd.orden_trabajo_id = ot.codigo
                                  AND ote.evento_id IN (13, 5)
                                  AND DATE(ote.fecha_m) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "') as total_descargas,
                                 (SELECT COUNT(DISTINCT a.codigo) 
                                  FROM orden_trabajos_archivos ota
                                  INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                  WHERE (ota.ot_id = ot.codigo 
                                         OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                         OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                      INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                      WHERE otd.orden_trabajo_id = ot.codigo))) as total_archivos,
                                 COUNT(DISTINCT ot.codigo) as total_ot,
                                 SUM(CASE WHEN ot.estado_ing = 1 THEN 1 ELSE 0 END) as ot_cerradas,
                                 SUM(CASE WHEN ot.estado_ing != 1 OR ot.estado_ing IS NULL THEN 1 ELSE 0 END) as ot_abiertas
                               FROM orden_trabajos ot
                               WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                               AND ot.cliente IS NOT NULL AND ot.cliente != ''
                               GROUP BY ot.cliente
                               ORDER BY (total_subidas + total_descargas) DESC
                               LIMIT 50";
            $query = $this->conn->prepare($sql_comparativo);
            $query->execute();
            $comparativo = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Alertas - Proyectos sin Actividad
            $sql_proyectos_sin_actividad = "SELECT DISTINCT ot.cliente as proyecto
                                           FROM orden_trabajos ot
                                           WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                           AND ot.cliente IS NOT NULL AND ot.cliente != ''
                                           AND NOT EXISTS (
                                               SELECT 1 FROM orden_trabajos_archivos ota
                                               INNER JOIN archivos a ON a.codigo = ota.archivo_id
                                               WHERE (ota.ot_id = ot.codigo 
                                                      OR ota.ot_detalle_id IN (SELECT codigo FROM orden_trabajos_detalles WHERE orden_trabajo_id = ot.codigo)
                                                      OR ota.ot_produccion_id IN (SELECT otp.codigo FROM orden_trabajos_produccion otp 
                                                                                   INNER JOIN orden_trabajos_detalles otd ON otp.ot_detalle_id = otd.codigo 
                                                                                   WHERE otd.orden_trabajo_id = ot.codigo))
                                               AND DATE(a.fecha_hora) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                           )
                                           AND NOT EXISTS (
                                               SELECT 1 FROM orden_trabajos_eventos ote
                                               INNER JOIN orden_trabajos_detalles otd ON ote.ot_detalle_id = otd.codigo
                                               WHERE otd.orden_trabajo_id = ot.codigo
                                               AND ote.evento_id IN (13, 5)
                                               AND DATE(ote.fecha_m) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                                           )
                                           LIMIT 20";
            $query = $this->conn->prepare($sql_proyectos_sin_actividad);
            $query->execute();
            $proyectos_sin_actividad = $query->fetchAll(PDO::FETCH_ASSOC);
            
            // Alertas - Proyectos con OT Atrasadas
            $sql_proyectos_ot_atrasadas = "SELECT DISTINCT ot.cliente as proyecto,
                                                           COUNT(DISTINCT ot.codigo) as ot_atrasadas
                                          FROM orden_trabajos ot
                                          WHERE DATE(ot.fecha) BETWEEN '" . $fecha_desde . "' AND '" . $fecha_hasta . "'
                                          AND ot.cliente IS NOT NULL AND ot.cliente != ''
                                          AND ot.fecha_entrega IS NOT NULL 
                                          AND DATE(ot.fecha_entrega) < CURDATE()
                                          AND (ot.estado_ing != 1 OR ot.estado_ing IS NULL)
                                          GROUP BY ot.cliente
                                          ORDER BY ot_atrasadas DESC
                                          LIMIT 20";
            $query = $this->conn->prepare($sql_proyectos_ot_atrasadas);
            $query->execute();
            $proyectos_ot_atrasadas = $query->fetchAll(PDO::FETCH_ASSOC);
            
            return array(
                'kpis' => array(
                    'total_proyectos' => $total_proyectos,
                    'proyectos_con_ot' => $proyectos_con_ot,
                    'proyectos_sin_actividad' => $proyectos_sin_actividad
                ),
                'actividad_proyectos' => $actividad_proyectos,
                'documentacion_proyectos' => $documentacion_proyectos,
                'ot_por_proyecto_estado' => $ot_por_proyecto_estado,
                'tipos_proyecto' => $tipos_proyecto,
                'actividad_temporal' => $actividad_temporal,
                'comparativo' => $comparativo,
                'alertas' => array(
                    'proyectos_sin_actividad' => $proyectos_sin_actividad,
                    'proyectos_ot_atrasadas' => $proyectos_ot_atrasadas
                ),
                'proyecto_seleccionado' => $proyecto_seleccionado,
                'granularidad' => $granularidad
            );
        } catch (PDOException $e) {
            return array('error' => $e->getMessage());
        }
    }
}

?>
