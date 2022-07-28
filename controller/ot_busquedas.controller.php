<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Ot_busquedasController::singleton_ot_busquedas();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST['ot']);
}

function getRegistrosFiltroSeguimiento(){
    $controlador = Ot_busquedasController::singleton_ot_busquedas();
    
    echo $controlador->getRegistrosFiltroSeguimiento($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST['ot'], $_POST['area'], $_POST['estado']);
}

function getRegistrosFiltroSeguimientoArchivos(){
    $controlador = Ot_busquedasController::singleton_ot_busquedas();
    
    echo $controlador->getRegistrosFiltroSeguimientoArchivos($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST['ot'], $_POST['area'], $_POST['estado']);
}

function addOt_busqueda() {
    $controlador = Ot_busquedasController::singleton_ot_busquedas();
    
    echo $controlador->addOt_busqueda  (    
                                        $_POST['item'],
                                        $_POST['cantidad'],
                                        $_POST['seccion'],
                                        $_POST['sector'],
                                        $_POST['estado'],
                                        $_POST['prioridad'],
                                        $_POST['ot'],
                                        $_POST['pu'],
                                        $_POST['pucant'],
                                        $_POST['puneto'],
                                        $_POST['clasificacion'],
                                        $_POST['observaciones']
            );
}

function updateOt_busqueda() {
    $controlador = Ot_busquedasController::singleton_ot_busquedas();
    
    echo $controlador->updateOt_busqueda(    $_POST['codigo'], 
                                            $_POST['item'],
                                            $_POST['cantidad'],
                                            $_POST['seccion'],
                                            $_POST['sector'],
                                            $_POST['estado'],
                                            $_POST['prioridad'],
                                            $_POST['ot'],
                                            $_POST['pu'],
                                            $_POST['pucant'],
                                            $_POST['puneto'],
                                            $_POST['clasificacion'],
                                            $_POST['observaciones']
            );
}

function deleteOt_busqueda() {
    $controlador = Ot_busquedasController::singleton_ot_busquedas();
    
    echo $controlador->deleteOt_busqueda($_POST['codigo']);
}

function getOt_busqueda() {
    $controlador = Ot_busquedasController::singleton_ot_busquedas();
    
    echo $controlador->getOt_busqueda($_POST['codigo']);
}

class Ot_busquedasController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/ot_busquedas.model.php";
            $this->conn = Ot_busquedasModel::singleton_ot_busquedas();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_busquedas() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_busquedas(){
        return intval($this->conn->getCountOt_busquedas()[0]);
        
    }
    
    public function getMenuDestinos($rol){
        return $this->conn->getMenuDestinos($rol);
    }
    
    public function getRegistrosFiltroSeguimiento($orderby, $sentido, $registros, $pagina, $busqueda, $ot, $area, $estado){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        $_SESSION['ot'] = $ot;
        $_SESSION['area'] = $area;
        $_SESSION['estado'] = $estado;
                                     
        $menu_user_destinos = $this->getMenuDestinos($_SESSION["rol"]); 
        
        $roles = $this->getRoles();
        $destinos = $this->getDestinos();
        $_sectores = $this->getSectores();
        $_secciones = $this->getSecciones();
        $secciones = []; 
        $sectores = []; 

        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot, $estado);

        foreach($devuelve as $dev){
            $secciones[$dev["seccion"]]["codigo"] = $dev["seccion"];
            $secciones[$dev["seccion"]]["descripcion"] = $dev["seccion"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["codigo"] = $dev["sector"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["descripcion"] = $dev["sector"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["registros"][] = $dev;
            /*
            foreach($_secciones as $secc){
                if ($dev["seccion_id"] == $secc["codigo"]){
                    $secciones[$secc["codigo"]]["codigo"] = $secc["codigo"];
                    $secciones[$secc["codigo"]]["descripcion"] = $secc["descripcion"];                            
                    foreach($_sectores as $sect){
                        if ($dev["sector_id"] == $sect["codigo"]){
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["codigo"] = $sect["codigo"];       
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["descripcion"] = $sect["descripcion"];  
                            
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["registros"][] = $dev;          
                        }
                    }                              
                }
            }*/
        }

        $prods = $this->conn->getPartesProduccions($ot);
        foreach($prods as $key => $prod){
            // Aca hay q cambiar
            /*$destinos_otps = $this->conn->getOtpsArchivosDestinos($prod["codigo"]);
            foreach($destinos_otps as $dtops){
                if ($dtops["cuenta"] > 0) {
                    $prods[$key]["destinos"][$dtops["codigo"]] = $dtops["cuenta"];
                }
            }*/
        }

        if ($_SESSION["rol"] > 1) {
            foreach($destinos as $pos => $dest){
                $encuentra = 0;
                foreach($menu_user_destinos as $mud){
                    if ($mud["destino_id"] == $dest["codigo"]){
                        if ($mud["permiso"] > 0){
                            //if ($_SESSION['area'] == $dest["codigo"]) {
                                $encuentra = 1;
                                $destinos[$pos]["permiso"] = $dest["permiso"];
                            //}
                        }
                    }
                }
                if ($encuentra == 0){
                    unset($destinos[$pos]);
                }
            }
        } else {
            foreach($destinos as $pos => $dest){
                $encuentra = 0;
                /*if ($_SESSION['area'] == $dest["codigo"]) {
                    $encuentra = 1;
                    $destinos[$pos]["permiso"] = $dest["permiso"];
                }
                if ($encuentra == 0){
                    unset($destinos[$pos]);
                }*/
            }
        }

        $estados = $this->conn->getPartesProduccionsEstados($ot);
        /* Aca hay que traer todos los archivos por linea de OTP (Prod. Estandar y Prod. Custom), verificar los archivos por OTD y verificar por OT general. */
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
        WHERE otp.ot_busqueda_id = otd.codigo and otd.orden_trabajo_id = ot.codigo and ot.codigo = " . intval($ot);
        $archivos = $this->conn->ejecutarSql($parte_sql);
        
        $registros = $devuelve;

        foreach($prods as $key => $prod){
            $prods[$key]["destinos_cuenta"] = [];
            foreach($archivos as $a) {
                if ($a["codigo"] != $prod["codigo"] ) {
                    continue;
                }
                foreach ($destinos as $k => $d){
                    $prods[$key]["destinos_cuenta"][$d["codigo"]] += $a["cuenta_".$d["codigo"]];
                }
            }
        }
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_busquedas.seguimiento.template.php";
        
    }
    
    public function getRegistrosFiltroSeguimientoArchivos($orderby, $sentido, $registros, $pagina, $busqueda, $ot, $area, $estado){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        $_SESSION['ot'] = $ot;
        $_SESSION['area'] = $area;
        $_SESSION['estado'] = $estado;
                                     
        $menu_user_destinos = $this->getMenuDestinos($_SESSION["rol"]); 
        
        $roles = $this->getRoles();
        $destinos = $this->getDestinos();
        $_sectores = $this->getSectores();
        $_secciones = $this->getSecciones();
        $secciones = []; 
        $sectores = []; 

        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot, $estado);

        foreach($devuelve as $dev){
            $secciones[$dev["seccion"]]["codigo"] = $dev["seccion"];
            $secciones[$dev["seccion"]]["descripcion"] = $dev["seccion"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["codigo"] = $dev["sector"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["descripcion"] = $dev["sector"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["registros"][] = $dev;
        }

        $prods = $this->conn->getPartesProduccions($ot);

        if ($_SESSION["rol"] > 1) {
            foreach($destinos as $pos => $dest){
                $encuentra = 0;
                foreach($menu_user_destinos as $mud){
                    if ($mud["destino_id"] == $dest["codigo"]){
                        if ($mud["permiso"] > 0){
                            //if ($_SESSION['area'] == $dest["codigo"]) {
                                $encuentra = 1;
                                $destinos[$pos]["permiso"] = $dest["permiso"];
                            //}
                        }
                    }
                }
                if ($encuentra == 0){
                    unset($destinos[$pos]);
                }
            }
        } 

        $estados = $this->conn->getPartesProduccionsEstados($ot);
        $archivos = $this->conn->getArchivos();
        
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_busquedas.seguimiento.archivos.php";
        
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        $_SESSION['ot'] = $ot;
        
        $roles = $this->getRoles();
        $destinos = $this->getDestinos();
        $_sectores = $this->getSectores();
        $_secciones = $this->getSecciones();
        $secciones = []; 
        $sectores = []; 

        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $ot);

        $archivos = $this->conn->getArchivos();

        $prods = $this->conn->getPartesProduccions($ot);
        $ot_header = $this->conn->getOt_header($ot)[0];

        foreach($devuelve as $dev){
            $secciones[$dev["seccion"]]["codigo"] = $dev["seccion"];
            $secciones[$dev["seccion"]]["descripcion"] = $dev["seccion"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["codigo"] = $dev["sector"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["descripcion"] = $dev["sector"];
            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["registros"][] = $dev;

            foreach($prods as $prod){
                if ($prod["ot_detalle_id"] == $dev["codigo"]){
                    foreach($archivos as $archi){
                        if ($archi["ot_produccion_id"] == $prod["codigo"]){
                            $secciones[$dev["seccion"]]["sectores"][$dev["sector"]]["archivos"][$dev["codigo"]][] = $archi;
                        }
                    }
                }
            }

            /*
            foreach($_secciones as $secc){
                if ($dev["seccion_id"] == $secc["codigo"]){
                    $secciones[$secc["codigo"]]["codigo"] = $secc["codigo"];
                    $secciones[$secc["codigo"]]["descripcion"] = $secc["descripcion"];                            
                    foreach($_sectores as $sect){
                        if ($dev["sector_id"] == $sect["codigo"]){
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["codigo"] = $sect["codigo"];       
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["descripcion"] = $sect["descripcion"];  
                            
                            $secciones[$secc["codigo"]]["sectores"][$sect["codigo"]]["registros"][] = $dev;          
                        }
                    }                              
                }
            }
            */
        } 
        
        $registros = $devuelve;        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_busquedas.busqueda.template.php";
        
    }
    
    public function getOt_busqueda($codigo) {
        $devuelve = $this->conn->getOt_busqueda($codigo);
        
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
    
    public function getOts() {
        $devuelve = $this->conn->getOts();
        
        return $devuelve;
        
    }
    
    public function getUnidades() {
        $devuelve = $this->conn->getUnidades();
        
        return $devuelve;
        
    }
    public function getProductosA() {
        $devuelve = $this->conn->getProductosA();
        
        return $devuelve;
        
    }
    
    public function getProductosB() {
        $devuelve = $this->conn->getProductosB();
        
        return $devuelve;
        
    }
    
    public function getProductosC() {
        $devuelve = $this->conn->getProductosC();
        
        return $devuelve;
        
    }
    
    public function getProductosD() {
        $devuelve = $this->conn->getProductosD();
        
        return $devuelve;
        
    }
    
    public function getProductosE() {
        $devuelve = $this->conn->getProductosE();
        
        return $devuelve;
        
    }
    
    public function getProductosF() {
        $devuelve = $this->conn->getProductosF();
        
        return $devuelve;
        
    }
    
    public function getProductosP() {
        $devuelve = $this->conn->getProductosP();
        
        return $devuelve;
        
    }
    
    public function getProductosS() {
        $devuelve = $this->conn->getProductosS();
        
        return $devuelve;
        
    }
    
    public function getRoles() {
        $devuelve = $this->conn->getRoles();
        
        return $devuelve;
        
    }
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();
        
        return $devuelve;
        
    }
    public function formatNumber($prod){
        $prod["numero"] = $prod["codigo"];
        while (strlen($prod["numero"]) < 5 ){
            $prod["numero"] = "0" . $prod["numero"];
        }
        return $prod;
    }
    
    public function getOtArchivos($codigo) {
        $devuelve = $this->conn->getOtArchivos($codigo);
        
        return $devuelve;
        
    }
    
    public function getOt($codigo) {
        $devuelve = $this->conn->getOt($codigo);
        
        return $devuelve;
        
    }
}
