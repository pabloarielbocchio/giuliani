<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->addOt_produccion  (    
                                        $_POST['avance'],
                                        $_POST['cantidad'],
                                        $_POST['standard'],
                                        $_POST['prod_id'],
                                        $_POST['personalizado_id'],
                                        $_POST['unidad'],
                                        $_POST['estado'],
                                        $_POST['prioridad'],
                                        $_POST['detalle'],
                                        $_POST['observaciones']
            );
}

function updateOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->updateOt_produccion(    $_POST['codigo'], 
                                                $_POST['avance'],
                                                $_POST['cantidad'],
                                                $_POST['standard'],
                                                $_POST['prod_id'],
                                                $_POST['personalizado_id'],
                                                $_POST['unidad'],
                                                $_POST['estado'],
                                                $_POST['prioridad'],
                                                $_POST['detalle'],
                                                $_POST['observaciones']
            );
}

function updateOt_produccionEstado() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->updateOt_produccionEstado(    $_POST['codigo'], 
                                                $_POST['atributo'],
                                                $_POST['avance'],
                                                $_POST['estado'],
                                                $_POST['code'],
                                                $_POST['ing_alcance'],
                                                $_POST['ing_planos'],
                                                $_POST['destino']
            );
}

function deleteOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->deleteOt_produccion($_POST['codigo']);
}

function restablecerOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->restablecerOt_produccion($_POST['codigo']);
}

function getOt_produccion() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->getOt_produccion($_POST['codigo']);
}

function getOt_produccionEstado() {
    $controlador = Ot_produccionsController::singleton_ot_produccions();
    
    echo $controlador->getOt_produccionEstado($_POST['codigo'], $_POST['atributo'], $_POST["destino"], $_POST["code"]);
}

class Ot_produccionsController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/ot_produccions.model.php";
            $this->conn = Ot_produccionsModel::singleton_ot_produccions();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_produccions() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_produccions(){
        return intval($this->conn->getCountOt_produccions()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_produccions.busqueda.template.php";
        
    }
    
    public function addOt_produccion($avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones) {
        $devuelve = $this->conn->addOt_produccion($avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones);
        if ($devuelve === 0){
            $codigo = $this->conn->getLastOtProduccion()[0]["codigo"];
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){

                $observaciones = "Actualizacion Estado OT Produccion. " . $otp["prod_standar"] . $otp["prod_personalizado"];
                //$this->conn->addOt_estado($codigo, 1, null, $observaciones, 'ingenieria');
                
                $evento = 9;
                $observaciones = "Generacion OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
            }
        }
        return $devuelve;
        
    }
    
    public function updateOt_produccion($codigo, $avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones) {
        $devuelve = $this->conn->updateOt_produccion($codigo, $avance, $cantidad, $standard, $prod_id, $personalizado_id, $unidad, $estado, $prioridad, $detalle, $observaciones);
        if ($devuelve === 0){
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){
                $evento = 11;
                $observaciones = "Modificaion OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
            }
        }
        return $devuelve;        
    }
    
    public function updateOt_produccionEstado($codigo, $atributo, $avance, $estado, $code, $ing_alcance, $ing_planos, $destino) { //aca quede
        if ($code == 0){ 
            $devuelve = $this->conn->insertOt_produccionEstado($codigo, $atributo, $avance, $estado, $code, intval($ing_alcance), intval($ing_planos), $destino); 
            if ($devuelve === 0){
                //$codigo = $this->conn->getLastOtProduccion()[0]["codigo"];
            }
        } else {
            $ot_prod_estado_antes = $this->conn->getOt_produccionEstadoCode($code)[0];
            $devuelve = $this->conn->updateOt_produccionEstado($codigo, $atributo, $avance, $estado, $code, intval($ing_alcance), intval($ing_planos));    
        }
        $destinos = $this->conn->getDestinos();
        $_estado = $this->conn->getEstado($estado)[0];
        if ($devuelve === 0){
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){
                $evento = 11;
                $observaciones = "Actualizacion Estado OT Produccion (" . $_estado["descripcion"] . ") " . $otp["prod_standar"] . $otp["prod_personalizado"];
                if ($estado == 2){ // en proceso
                    $observaciones = "Actualizacion Estado OT Produccion (" . $_estado["descripcion"] . " - " . $avance . "%) " . $otp["prod_standar"] . $otp["prod_personalizado"];
                }
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
                /* 
                    Aca hay que agregar que cuando se hace el cambio, se verifica todas las OTP del OTD, 
                    y si hay al menos uno que no es EN COLA => ENCURSO; 
                    si todos estan APRO => FINALIZAR; 
                    los Anulados omitirlos 

                    public function finalizarOtDetalle_listado($codigo) {
                    public function abrirOtDetalle_listado($codigo) {

                    El estado finalizado automatico esta dificil de hacer, primero hay que verificar los destinos que le corresponden => siempre_visibles + archivos si tienen destinos habilitados
                */
                $otps = $this->conn->getOtps($ult_detalle);
                $encurso = 0;
                $finalizada = 1;    
                
                // Consulta
                $parte_sql = "SELECT 
                pns.descripcion AS standar,
                pnp.descripcion AS personalizado,
                pnf.descripcion AS conf,";
                foreach($destinos as $pos => $dest){
                    $cod_dest = $dest["codigo"];
                    $parte_sql .= "(SELECT COUNT(*) FROM archivo_destinos ad WHERE ad.archivo_id = a.codigo AND ad.destino_id = " . $cod_dest . ") AS cuenta_" . $cod_dest . ",";
                }
                $parte_sql .= " otp.codigo 
                FROM 
                    orden_trabajos ot,
                    orden_trabajos_detalles otd,
                    orden_trabajos_produccion otp
                        LEFT JOIN productos_personalizados pnp ON otp.prod_personalizado_id = pnp.codigo
                        LEFT JOIN productos_estandar pns ON otp.prod_estandar_id = pns.codigo
                        LEFT JOIN productos_configuraciones pnconf ON pns.codigo = pnconf.prod_standar_id
                        LEFT JOIN productos_nivel_f pnf ON pnf.codigo = pnconf.prod_f_id
                        LEFT JOIN productos_nivel_d pnd ON pnd.codigo = pns.cod_prod_nd
                        LEFT JOIN productos_nivel_c pnc ON pnc.codigo = pnd.cod_prod_nc
                        LEFT JOIN productos_nivel_b pnb ON pnb.codigo = pnc.cod_prod_nb
                        LEFT JOIN productos_nivel_a pna ON pna.codigo = pnb.cod_prod_na
                        LEFT JOIN archivos a ON 
                            (
                                a.cod_prod_na = pna.codigo OR 
                                a.cod_prod_nb = pnb.codigo OR 
                                a.cod_prod_nc = pnc.codigo OR 
                                a.cod_prod_nd = pnd.codigo OR 
                                a.cod_prod_nf = pnf.codigo OR 
                                a.cod_prod_personalizado_id=pnp.codigo
                            )
                WHERE otp.ot_detalle_id = otd.codigo and otd.orden_trabajo_id = ot.codigo and otd.codigo = " . intval($ult_detalle);                        
                $archivos = $this->conn->ejecutarSql($parte_sql);
                
                foreach($otps as $aux){
                    $cod_otp = $aux["codigo"];
                    $otpd = $this->conn->getOtpsDestinos($cod_otp);
                    foreach($otpd as $ote){
                        if ($ote["estado_id"] == 2 or $ote["estado_id"] == 3){ // En Curso o APRO
                            $encurso = 1;
                            break;
                        }
                    }
                    if ($encurso == 1){
                        break;
                    }
                }

                foreach($otps as $key => $prod){
                    foreach ($destinos as $k => $destino){
                        if($destino["siempre_visible"] == 1){
                            $status = $this->conn->getOtpsDestinoSingle($prod["codigo"], $destino["codigo"])[0]["estado_id"];
                            if ($status != 3 and $status != 4){
                                $finalizada = 0; 
                            }
                        } else {
                            foreach($archivos as $a) {
                                if ($a["codigo"] != $prod["codigo"] ) {
                                    continue;
                                }
                                if ($a["cuenta_".$destino["codigo"]] > 0){
                                    $status = $this->conn->getOtpsDestinoSingle($prod["codigo"], $destino["codigo"])[0]["estado_id"];
                                    if ($status != 3 and $status != 4){
                                        $finalizada = 0; 
                                        break;
                                    }
                                }
                            }
                        }
                        if ($finalizada == 0){
                            break;
                        }
                    }                    
                    if ($finalizada == 0){
                        break;
                    }
                }

                if ($encurso == 1){
                    if ($finalizada == 0){
                        $resultado = $this->abrirOtDetalle_listado($ult_detalle);
                    } else {
                        $resultado = $this->finalizarOtDetalle_listado($ult_detalle);
                    }
                } 
            }          
        }
        return $devuelve;
        
    }
    
    public function restablecerOt_produccion($codigo) {
        $devuelve = $this->conn->deleteOt_produccionEstados($codigo);
        //$devuelve = $this->conn->updateOt_produccionEstadoAll($codigo, 'ingenieria', 0, 1);        
        //$_estado = $this->conn->getEstado(1)[0];
        if ($devuelve === 0){
            $otp = $this->conn->getOt_produccion($codigo)[0];
            $ult_detalle = $otp["ot_detalle_id"];
            if ($ult_detalle > 0){
                $evento = 12;
                $observaciones = "Restablecer Progreso OT Produccion " . $otp["prod_standar"] . $otp["prod_personalizado"];
                $this->conn->addOt_evento($ult_detalle, $codigo, $evento, 0, $observaciones);
            }
        }
        return $devuelve;
        
    }
    
    public function deleteOt_produccion($codigo) {
        $devuelve = $this->conn->deleteOt_produccion($codigo);
        
        return $devuelve;
        
    }
    
    public function getOt_produccion($codigo) {
        $devuelve = $this->conn->getOt_produccion($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getOt_produccionEstado($codigo, $atributo, $destino, $code = 0) {
        if ($code > 0) {
            $devuelve = $this->conn->getOt_produccionEstadoCode($code);
            
        } else {
            $devuelve = $this->conn->getOt_produccionEstado($codigo, $atributo, $destino);

        }        
        return json_encode($devuelve[0]);
        
    }
    
    public function getSecciones() {
        $devuelve = $this->conn->getSecciones();
        
        return $devuelve;
        
    }
    
    public function getSectores() {
        $devuelve = $this->conn->getSectores();
        
        return $devuelve;
        
    }
    
    public function getEstados() {
        $devuelve = $this->conn->getEstados();
        
        return $devuelve;
        
    }
    
    public function getPrioridades() {
        $devuelve = $this->conn->getPrioridades();
        
        return $devuelve;
        
    }
    
    public function getStandards() {
        $devuelve = $this->conn->getStandards();
        
        return $devuelve;
        
    }
    
    public function getPersonalizados() {
        $devuelve = $this->conn->getPersonalizados();
        
        return $devuelve;
        
    }
    
    public function getUnidades() {
        $devuelve = $this->conn->getUnidades();
        
        return $devuelve;
        
    }
    
    public function getOts() {
        $devuelve = $this->conn->getOts();
        
        return $devuelve;
        
    }
    
    public function getOtsDetalles() {
        $devuelve = $this->conn->getOtsDetalles();
        
        return $devuelve;
        
    }    
    
    public function finalizarOtDetalle_listado($codigo) {
        $item = $this->conn->getOt_listado($codigo)[0];
        $devuelve = $this->conn->finalizarOtDetalle_listado($codigo);
        if ($devuelve == 0){
            $ult_detalle = $codigo;
            if ($ult_detalle > 0){
                $evento = 6;
                $observaciones = "Finalizacion Item Vendido " . $item["item_vendido"];
                $this->conn->addOt_evento($ult_detalle, 0, $evento, 0, $observaciones);
            }
        }        
        return $devuelve;
        
    }
    
    public function abrirOtDetalle_listado($codigo) {
        $item = $this->conn->getOt_listado($codigo)[0];
        $devuelve = $this->conn->abrirOtDetalle_listado($codigo);
        if ($devuelve == 0){
            $ult_detalle = $codigo;
            if ($ult_detalle > 0){
                $evento = 6;
                $observaciones = "Reapertura Item Vendido " . $item["item_vendido"];
                $this->conn->addOt_evento($ult_detalle, 0, $evento, 0, $observaciones);
            }
        }        
        return $devuelve;
        
    }
}
