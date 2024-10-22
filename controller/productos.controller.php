<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function buscarArchivosTablaOtp(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosTablaOtp(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['valorCodigoPP']
        );
}

function buscarArchivosTablaOtd(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosTablaOtd(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function buscarArchivosTablaOt(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosTablaOt(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function buscarArchivosTabla(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosTabla(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['grupo']
        );
}

function buscarArchivosOt(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosOt(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function buscarArchivosOtp(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosOtp(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function buscarArchivosOtd(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosOtd(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function buscarArchivos(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivos(
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['grupo']
        );
}

function buscarArchivosP(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->buscarArchivosP(
            $_POST['opc']
        );
}

function getRegistrosFiltroOpciones(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroOpciones(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4']
        );
}

function getRegistrosFiltroFilesOpciones(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesOpciones(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['otd']
        );
}

function getRegistrosFiltroFilesOpcionesView(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesOpcionesView(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['otd']
        );
}

function getRegistrosFiltroFilesOtd(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesOtd(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['readonly']
        );
}

function getRegistrosFiltroFilesOt(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesOt(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['readonly']
        );
}

function getRegistrosFiltroFilesOtp(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesOtp(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['readonly']
        );
}

function getRegistrosFiltroFilesArchivos(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesArchivos(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function getRegistrosFiltroFilesArchivosMapa(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesArchivosMapa(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function getRegistrosFiltroEventosOtp(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroEventosOtp(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc']
        );
}

function getRegistrosFiltroFiles(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFiles(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4'],
            $_POST['opc'],
            $_POST['grupo']
        );
}

function getRegistrosFiltroFilesP(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltroFilesP(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['opc']
        );
}

function getRegistrosFiltro(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltro(
            $_POST['orderby'], 
            $_POST['sentido'], 
            $_POST['registros'], 
            $_POST['pagina'], 
            $_POST['busqueda'],
            $_POST['select_n1'],
            $_POST['select_n2'],
            $_POST['select_n3'],
            $_POST['select_n4']
        );
}

function addProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->addProducto  (    
                                        $_POST['descripcion'],
                                        $_POST['select_n1'],
                                        $_POST['select_n2'],
                                        $_POST['select_n3'],
                                        $_POST['select_n4']
            );
}

function addProductoAlt() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->addProductoAlt  (    
                                        $_POST['descripcion'],
                                        $_POST['opcion']
            );
}

function updateProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->updateProducto(    $_POST['codigo'], 
                                        $_POST['descripcion'],
                                        $_POST['select_n1'],
                                        $_POST['select_n2'],
                                        $_POST['select_n3'],
                                        $_POST['select_n4']
            );
}

function updateProductoAlt() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->updateProductoAlt(    $_POST['codigo'], 
                                        $_POST['descripcion']
            );
}

function deleteProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->deleteProducto($_POST['codigo'],
                                    $_POST['select_n1'],
                                    $_POST['select_n2'],
                                    $_POST['select_n3'],
                                    $_POST['select_n4']);
}

function deleteProductoAlt() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->deleteProductoAlt($_POST['codigo']);
}

function mostrarArchivos() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->mostrarArchivos(
                                $_POST['estado'],
                                $_POST['codigo'],
                                $_POST['nivel']);
}

function cambiar_estadoProductoParam() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->cambiar_estadoProductoParam(
                                $_POST['estado'],
                                $_POST['codigo'],
                                $_POST['nivel']);
}

function cambiar_estadoProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->cambiar_estadoProducto(
                                $_POST['estado'],
                                $_POST['codigo'],
                                $_POST['select_n1'],
                                $_POST['select_n2'],
                                $_POST['select_n3'],
                                $_POST['select_n4']);
}

function cambiar_estadoOpcion() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->cambiar_estadoOpcion(
                                $_POST['estado'],
                                $_POST['codigo'],
                                $_POST['select_n1'],
                                $_POST['select_n2'],
                                $_POST['select_n3'],
                                $_POST['select_n4']);
}

function getProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getProducto($_POST['codigo'],
                                $_POST['select_n1'],
                                $_POST['select_n2'],
                                $_POST['select_n3'],
                                $_POST['select_n4']);
}

function getProductoAlt() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getProductoAlt($_POST['codigo']);
}

function getProductosBOnly() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getProductosBOnly($_POST['codigo']);
}

function getProductosCOnly() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getProductosCOnly($_POST['codigo']);
}

function getProductosDOnly() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getProductosDOnly($_POST['codigo']);
}

class ProductosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/productos.model.php";
            $this->conn = ProductosModel::singleton_productos();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_productos() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountProductos(){
        return intval($this->conn->getCountProductos()[0]);
        
    }
    
    public function deleteArchivo($codigo) {
        $devuelve = $this->conn->deleteArchivo($codigo);
        
        return $devuelve;
        
    }
    
    public function buscarArchivosTabla($n1, $n2, $n3, $n4, $opc, $grupo){                
        $n5 = 0;
        $n6 = 0;
        if ($grupo > 0){
            $n5 = $grupo;
            $n6 = $opc;
        }
        if ($opc > 0){
            if ($n1 == 0){
                $n1 = $opc;
                $n2 = $n3 = $n4 = 0;
            } elseif ($n2 == 0){
                $n2 = $opc;
                $n3 = $n4 = 0;
            } elseif ($n3 == 0){
                $n3 = $opc;
                $n4 = 0;
            } elseif ($n4 == 0){
                $n4 = $opc;
            }
        }
    
        $archivos = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);

        foreach($archivos as $archivo){
            if (!file_exists("../".$archivo["ruta"])){
                $this->deleteArchivo($archivo["codigo"]);
            }
        }
        
        $archivos = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);
        
        foreach($archivos as $k => $archivo){
            if(@is_array(getimagesize("../".$archivo["ruta"]))){
                $archivos[$k]["imagen"] = true;
            } else {
                $archivos[$k]["imagen"] = false;
            }
        }
        $portada = $this->getOtsportada();               
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        $destinos = $this->getDestinos();
        $archivos_destinos = $this->getArchivosDestinos();

        // buscar las ot'detalles donde el/los archivos impacten
        $detalles = $this->getDetalles();
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();
        $prod_e = $this->getProductosE();
        $prod_f = $this->getProductosF();
        $prod_p = $this->getProductosP();
        $prod_s = $this->getProductosS();
        //$prod_config = $this->conn->getProductosX($prod_estandar_id);

        $reg2 = [];
        
        foreach($detalles as $dev){
            $ot_detalle_id = $dev["ot_detalle_id"];
            $prod_estandar_id = $dev["prod_estandar_id"];
            $prod_personalizado_id = $dev["prod_personalizado_id"];

            $cod_prod_nf = -1;
            $cod_prod_ne = -1;
            $cod_prod_nd = -1;
            $cod_prod_nc = -1;
            $cod_prod_nb = -1;
            $cod_prod_na = -1;

            /*foreach($prod_x as $aux){
                if ($aux["prod_standar_id"] == $prod_estandar_id){
                    $cod_prod_nf[] = $aux["prod_f_id"];
                    break;
                }
            }*/

            foreach($prod_s as $aux){
                if ($aux["codigo"] == $prod_estandar_id){
                    $cod_prod_nd = $aux["cod_prod_nd"];
                    break;
                }
            }

            foreach($prod_d as $aux){
                if ($aux["codigo"] == $cod_prod_nd){
                    $cod_prod_nc = $aux["cod_prod_nc"];
                    break;
                }
            }

            foreach($prod_c as $aux){
                if ($aux["codigo"] == $cod_prod_nc){
                    $cod_prod_nb = $aux["cod_prod_nb"];
                    break;
                }
            }

            foreach($prod_b as $aux){
                if ($aux["codigo"] == $cod_prod_nb){
                    $cod_prod_na = $aux["cod_prod_na"];
                    break;
                }
            }
        }

        foreach($archivos as $aux){
            if ($aux["cod_prod_na"] == $cod_prod_na and $n1 == $cod_prod_na){
                $reg2[$ot_detalle_id] = $dev;
            }
            if ($aux["cod_prod_nb"] == $cod_prod_nb and $n2 == $cod_prod_nb){
                $reg2[$ot_detalle_id] = $dev;
            }
            if ($aux["cod_prod_nc"] == $cod_prod_nc and $n3 == $cod_prod_nc){
                $reg2[$ot_detalle_id] = $dev;
            }
            if ($aux["cod_prod_nd"] == $cod_prod_nd and $n4 == $cod_prod_nd){
                $reg2[$ot_detalle_id] = $dev;
            }
            if ($aux["cod_prod_ne"] == $cod_prod_ne and $n5 == $cod_prod_ne){
                $reg2[$ot_detalle_id] = $dev;
            }
            /*foreach($cod_prod_nf as $f){
                if ($aux["cod_prod_nf"] == $f){
                    $registros[$aux["codigo"]] = $aux;
                }
            }*/
        }

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.tabla.php";
        
    }
        
    
    public function buscarArchivosTablaOtp($n1, $n2, $n3, $n4, $opc, $valorCodigoPP = null){        
        
       $archivos = $this->conn->getArchivosOtp($opc);
        
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();
        $prod_e = $this->getProductosE();
        $prod_f = $this->getProductosF();
        $prod_p = $this->getProductosP();
        $prod_s = $this->getProductosS();
      
        foreach($archivos as $pos => $archiv){
            if($archiv["cod_prod_nd"] > 0){
                $desc_aux = "";
                
                if($archiv["cod_prod_nf"] > 0){                
                    foreach($prod_e as $au){
                        if ($au["codigo"] == $archiv["cod_prod_ne"]){
                            $desc_aux .= "OPC => " . $au["descripcion"] . " | ";
                            break;
                        }
                    }
                    foreach($prod_f as $au){
                        if ($au["codigo"] == $archiv["cod_prod_nf"]){
                            $desc_aux .= "ALT => " . $au["descripcion"] . " - ";
                        }
                    }
                }
                foreach($prod_d as $aux){
                    if ($aux["codigo"] == $archiv["cod_prod_nd"]){
                        $desc = $desc_aux . $aux["descripcion"];
                        break;
                    }
                }
                $archivos[$pos]["dependencia"] = "N4 - " . $desc;
            
            }elseif ($archiv["cod_prod_nc"] > 0){
                foreach($prod_c as $aux){
                    if ($aux["codigo"] == $archiv["cod_prod_nc"]){
                        $desc = $aux["descripcion"];
                        break;
                    }
                }
                $archivos[$pos]["dependencia"] = "N3 - " . $desc;

            }elseif ($archiv["cod_prod_nb"] > 0){
                foreach($prod_b as $aux){
                    if ($aux["codigo"] == $archiv["cod_prod_nb"]){
                        $desc = $aux["descripcion"];
                        break;
                    }
                }
                $archivos[$pos]["dependencia"] = "N2 - " . $desc;
           
            }elseif ($archiv["cod_prod_na"] > 0){
                foreach($prod_a as $aux){
                    if ($aux["codigo"] == $archiv["cod_prod_na"]){
                        $desc = $aux["descripcion"];
                        break;
                    }
                }
                $archivos[$pos]["dependencia"] = "N1 - " . $desc;
       
            }elseif ($archiv["cod_prod_estandar_id"] > 0){
                foreach($prod_s as $aux){
                    if ($aux["codigo"] == $archiv["cod_prod_estandar_id"]){
                        $desc = $aux["descripcion"];
                        break;
                    }
                }
                $archivos[$pos]["nuevo"] = 1;
                $archivos[$pos]["dependencia"] = "STD NEW - " . $desc;
      
            }elseif ($archiv["cod_prod_personalizado_id"] > 0){
                foreach($prod_p as $aux){
                    if ($aux["codigo"] == $archiv["cod_prod_personalizado_id"]){
                        $desc = $aux["descripcion"];
                        break;
                    }
                }
                $archivos[$pos]["nuevo"] = 1;
                $archivos[$pos]["dependencia"] = "CUSTOM - " . $desc;
        
            }
        }
        $destinos = $this->getDestinos();
        $archivos_destinos = $this->getArchivosDestinos();
        $_SESSION["archivos_datos"]=$archivos;
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.tablaotp.php";
       // include $_SERVER['DOCUMENT_ROOT']."/Giuliani/PDF/portadapdf.php";
    }
        
    
    public function buscarArchivosTablaOtd($n1, $n2, $n3, $n4, $opc){        
        
        $archivos = $this->conn->getArchivosOtd($opc);
        foreach($archivos as $pos => $archiv){
            $archivos[$pos]["nuevo"] = 1;
        }
        
        $destinos = $this->getDestinos();
        $archivos_destinos = $this->getArchivosDestinos();

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.tablaotd.php";
        
    }
        
    
    public function buscarArchivosTablaOt($n1, $n2, $n3, $n4, $opc){        
        
        $archivos = $this->conn->getArchivosOt($opc);
        foreach($archivos as $pos => $archiv){
            $archivos[$pos]["nuevo"] = 1;
        }
        
        $destinos = $this->getDestinos();
        $archivos_destinos = $this->getArchivosDestinos();

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.tablaot.php";
        
    }
        
    
    public function buscarArchivos($n1, $n2, $n3, $n4, $opc, $grupo){        
        $n5 = 0;
        $n6 = 0;
        if ($grupo > 0){
            $n5 = $grupo;
            $n6 = $opc;
        }
        if ($opc > 0){
            if ($n1 == 0){
                $n1 = $opc;
                $n2 = $n3 = $n4 = 0;
            } elseif ($n2 == 0){
                $n2 = $opc;
                $n3 = $n4 = 0;
            } elseif ($n3 == 0){
                $n3 = $opc;
                $n4 = 0;
            } elseif ($n4 == 0){
                $n4 = $opc;
            }
        }

        $archivos = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);

        foreach($archivos as $archivo){
            if (!file_exists("../".$archivo["ruta"])){
                $this->deleteArchivo($archivo["codigo"]);
            }
        }
        
        $archivos = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);
        
        foreach($archivos as $k => $archivo){
            if(@is_array(getimagesize("../".$archivo["ruta"]))){
                $archivos[$k]["imagen"] = true;
            } else {
                $archivos[$k]["imagen"] = false;
            }
        }

        //var_dump($archivos);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.template.php";
        
    }
    
    public function buscarArchivosOtp($n1, $n2, $n3, $n4, $opc){        
        
        if ($opc > 0){
            if ($n1 == 0){
                $n1 = $opc;
                $n2 = $n3 = $n4 = 0;
            } elseif ($n2 == 0){
                $n2 = $opc;
                $n3 = $n4 = 0;
            } elseif ($n3 == 0){
                $n3 = $opc;
                $n4 = 0;
            } elseif ($n4 == 0){
                $n4 = $opc;
            }
        }

        $archivos = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);

        foreach($archivos as $archivo){
            if (!file_exists("../".$archivo["ruta"])){
                $this->deleteArchivo($archivo["codigo"]);
            }
        }
        
        $archivos = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);
        
        foreach($archivos as $k => $archivo){
            if(@is_array(getimagesize("../".$archivo["ruta"]))){
                $archivos[$k]["imagen"] = true;
            } else {
                $archivos[$k]["imagen"] = false;
            }
        }

        //var_dump($archivos);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.otp.php";
        
    }
    
    public function buscarArchivosOtd($n1, $n2, $n3, $n4, $opc){        
        
        $archivos = $this->conn->buscarArchivosOtd($n1, $n2, $n3, $n4, $opc);

        foreach($archivos as $archivo){
            if (!file_exists("../".$archivo["ruta"])){
                $this->deleteArchivo($archivo["codigo"]);
            }
        }
        
        $archivos = $this->conn->buscarArchivosOtd($n1, $n2, $n3, $n4, $opc);
        
        foreach($archivos as $k => $archivo){
            if(@is_array(getimagesize("../".$archivo["ruta"]))){
                $archivos[$k]["imagen"] = true;
            } else {
                $archivos[$k]["imagen"] = false;
            }
        }

        //var_dump($archivos);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.otp.php";
        
    }
    
    public function buscarArchivosOt($n1, $n2, $n3, $n4, $opc){        
        
        $archivos = $this->conn->buscarArchivosOt($n1, $n2, $n3, $n4, $opc);

        foreach($archivos as $archivo){
            if (!file_exists("../".$archivo["ruta"])){
                $this->deleteArchivo($archivo["codigo"]);
            }
        }
        
        $archivos = $this->conn->buscarArchivosOt($n1, $n2, $n3, $n4, $opc);
        
        foreach($archivos as $k => $archivo){
            if(@is_array(getimagesize("../".$archivo["ruta"]))){
                $archivos[$k]["imagen"] = true;
            } else {
                $archivos[$k]["imagen"] = false;
            }
        }

        //var_dump($archivos);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.otp.php";
        
    }
        
    public function buscarArchivosP($opc){        
        
        $archivos = $this->conn->buscarArchivosP($opc);

        foreach($archivos as $archivo){
            if (!file_exists("../".$archivo["ruta"])){
                $this->deleteArchivo($archivo["codigo"]);
            }
        }
        
        $archivos = $this->conn->buscarArchivosP($opc);
        
        foreach($archivos as $k => $archivo){
            if(@is_array(getimagesize("../".$archivo["ruta"]))){
                $archivos[$k]["imagen"] = true;
            } else {
                $archivos[$k]["imagen"] = false;
            }
        }

        //var_dump($archivos);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files_p.archivos.template.php";
        
    }
    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
                
        $_SESSION['n1'] = $n1;
        $_SESSION['n2'] = $n2;
        $_SESSION['n3'] = $n3;
        $_SESSION['n4'] = $n4;

        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/productos.busqueda.template.php";
        
    }
    
    public function getRegistrosFiltroFilesOpciones($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc, $otd){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        
        if ($opc > 0){
            if ($n1 == 0){
                $n1 = $opc;
                $n2 = $n3 = $n4 = 0;
            } elseif ($n2 == 0){
                $n2 = $opc;
                $n3 = $n4 = 0;
            } elseif ($n3 == 0){
                $n3 = $opc;
                $n4 = 0;
            } elseif ($n4 == 0){
                $n4 = $opc;
            }
        }
        
        
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();

        $pa = "";
        $pb = "";
        $pc = "";
        $pd = "";
        $unidad = 0;

        foreach($prod_a as $aux){
            if ($aux["codigo"] == $n1){
                $pa = $aux["descripcion"];
            }
        }

        foreach($prod_b as $aux){
            if ($aux["codigo"] == $n2){
                $pb = $aux["descripcion"];
            }
        }

        foreach($prod_c as $aux){
            if ($aux["codigo"] == $n3){
                $pc = $aux["descripcion"];
            }
        }

        foreach($prod_d as $aux){
            if ($aux["codigo"] == $n4){
                $pd = $aux["descripcion"];
                $unidad = $aux["unidad_id"];
            }
        }
                
        $_SESSION['n1'] = $n1;
        $_SESSION['n2'] = $n2;
        $_SESSION['n3'] = $n3;
        $_SESSION['n4'] = $n4;
        
        $prod_f = $this->getProductosF();
        $unidades = $this->getUnidades();

        $devuelve = $this->conn->getRegistrosFiltroOpciones($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.opciones.template.php";
        
    }
    
    
    public function getRegistrosFiltroFilesOpcionesView($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc, $otd){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        
        $ot_p = $this->conn->getOtp($opc)[0];
        $prod_estandar_id = $ot_p["prod_estandar_id"];
        
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();
        $prod_s = $this->getProductosS();
        $prod_config = $this->conn->getProductosX($prod_estandar_id);
        
        foreach($prod_s as $aux){
            if ($aux["codigo"] == $prod_estandar_id){
                $n4 = $aux["cod_prod_nd"];
                $ps = $aux["descripcion"];
            }
        }        
        foreach($prod_d as $aux){
            if ($aux["codigo"] == $n4){
                $unidad = $aux["unidad_id"];
                $n3 = $aux["cod_prod_nc"];
                $pd = $aux["descripcion"];
            }
        }
        foreach($prod_c as $aux){
            if ($aux["codigo"] == $n3){
                $n2 = $aux["cod_prod_nb"];
                $pc = $aux["descripcion"];
            }
        }
        foreach($prod_b as $aux){
            if ($aux["codigo"] == $n2){
                $n1 = $aux["cod_prod_na"];
                $pb = $aux["descripcion"];
            }
        }
        foreach($prod_a as $aux){
            if ($aux["codigo"] == $n1){
                $pa = $aux["descripcion"];
            }
        }
                
        $prod_f = $this->getProductosF();
        $unidades = $this->getUnidades();

        $devuelve = $this->conn->getRegistrosFiltroOpciones($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.opciones.view.template.php";
        
    }
    
    public function getRegistrosFiltroOpciones($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
                
        $_SESSION['n1'] = $n1;
        $_SESSION['n2'] = $n2;
        $_SESSION['n3'] = $n3;
        $_SESSION['n4'] = $n4;
        
        $prod_f = $this->getProductosF();

        $devuelve = $this->conn->getRegistrosFiltroOpciones($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/opciones.busqueda.template.php";
        
    }
    
    public function getRegistrosFiltroFiles($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc, $grupo){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
                
        $n5 = 0;
        $n6 = 0;
        if ($opc > 0){
            if ($n1 == 0){
                $n1 = $opc;
                $n2 = $n3 = $n4 = 0;
            } elseif ($n2 == 0){
                $n2 = $opc;
                $n3 = $n4 = 0;
            } elseif ($n3 == 0){
                $n3 = $opc;
                $n4 = 0;
            } elseif ($n4 == 0){
                $n4 = $opc;
            }
        }
        if ($grupo > 0){
            $n5 = $grupo;
            $n6 = $opc;
        }
        /*
        $_SESSION['n1'] = $n1;
        $_SESSION['n2'] = $n2;
        $_SESSION['n3'] = $n3;
        $_SESSION['n4'] = $n4;
        $_SESSION['opc'] = $opc;*/
        
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();
        $prod_e = $this->getProductosE();
        $prod_f = $this->getProductosF();

        $pa = "";
        $pb = "";
        $pc = "";
        $pd = "";
        $pe = "";
        $pf = "";

        foreach($prod_a as $aux){
            if ($aux["codigo"] == $n1){
                $pa = $aux["descripcion"];
            }
        }

        foreach($prod_b as $aux){
            if ($aux["codigo"] == $n2){
                $pb = $aux["descripcion"];
            }
        }

        foreach($prod_c as $aux){
            if ($aux["codigo"] == $n3){
                $pc = $aux["descripcion"];
            }
        }

        foreach($prod_d as $aux){
            if ($aux["codigo"] == $n4){
                $pd = $aux["descripcion"];
            }
        }

        if ($grupo > 0){
            foreach($prod_e as $aux){
                if ($aux["codigo"] == $grupo){
                    $pe = $aux["descripcion"];
                }
            }
            foreach($prod_f as $aux){
                if ($aux["codigo"] == $opc){
                    $pf = $aux["descripcion"];
                }
            }
        }
        
        //$devuelve = $this->conn->getRegistrosFiltroOpciones($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.busqueda.template.php";
        
    }
    
    public function getRegistrosFiltroFilesOtp($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc, $readonly){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        
        $archivos = $this->conn->getArchivosOtp($opc);

        $otp = $this->conn->getOtp($opc)[0];

        //var_dump($archivos);
        
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        $ot = $this->getOt(intval($_SESSION['ot']));

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.otp.template.php";
        
    }
    
    
    public function getRegistrosFiltroFilesOtd($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc, $readonly){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        
        $archivos = $this->conn->getArchivosOtp($opc);

        $otd = $this->conn->getOtd($opc)[0];

        //var_dump($archivos);
        
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.otd.template.php";
        
    }
    
    public function getRegistrosFiltroFilesOt($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc, $readonly){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;

        $archivos = $this->conn->getArchivosOt($opc);

        $otd = $this->conn->getOt($opc)[0];

        //var_dump($archivos);
        
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.ot.template.php";
        
    }
    
    public function getRegistrosFiltroFilesArchivos($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
                
        
        if ($opc > 0){
            if ($n1 == 0){
                $n1 = $opc;
                $n2 = $n3 = $n4 = 0;
            } elseif ($n2 == 0){
                $n2 = $opc;
                $n3 = $n4 = 0;
            } elseif ($n3 == 0){
                $n3 = $opc;
                $n4 = 0;
            } elseif ($n4 == 0){
                $n4 = $opc;
            }
        }
        /*
        $_SESSION['n1'] = $n1;
        $_SESSION['n2'] = $n2;
        $_SESSION['n3'] = $n3;
        $_SESSION['n4'] = $n4;
        $_SESSION['opc'] = $opc;*/
        
        $prod_a = $this->getProductosA();
        $prod_b = $this->getProductosB();
        $prod_c = $this->getProductosC();
        $prod_d = $this->getProductosD();
        $prod_e = $this->getProductosE();
        $prod_f = $this->getProductosF();

        $pa = "";
        $pb = "";
        $pc = "";
        $pd = "";
        $pe = "";
        $pf = "";

        foreach($prod_a as $aux){
            if ($aux["codigo"] == $n1){
                $pa = $aux["descripcion"];
            }
        }

        foreach($prod_b as $aux){
            if ($aux["codigo"] == $n2){
                $pb = $aux["descripcion"];
            }
        }

        foreach($prod_c as $aux){
            if ($aux["codigo"] == $n3){
                $pc = $aux["descripcion"];
            }
        }

        foreach($prod_d as $aux){
            if ($aux["codigo"] == $n4){
                $pd = $aux["descripcion"];
            }
        }
        
        if ($n1 > 0){
            $archivos_a = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);
        }        
        if ($n1 > 0){
            $archivos_a = $this->conn->buscarArchivos($n1, 0, 0, 0, $opc);
        }
        if ($n2 > 0){
            $archivos_b = $this->conn->buscarArchivos($n1, $n2, 0, 0, $opc);
        }
        if ($n3 > 0){
            $archivos_c = $this->conn->buscarArchivos($n1, $n2, $n3, 0, $opc);
        }
        if ($n4 > 0){
            $archivos_d = $this->conn->buscarArchivos($n1, $n2, $n3, $n4, $opc);
        }
        $archivos = [];
        foreach($archivos_d as $aux){
            $desc_ = $aux["descripcion"];
            $desc_aux = "";
            if ($aux["cod_prod_nf"] > 0){                        
                foreach($prod_e as $au){
                    if ($au["codigo"] == $aux["cod_prod_ne"]){
                        $desc_aux .= "OPC => " . $au["descripcion"] . " | ";
                        break;
                    }
                }
                foreach($prod_f as $au){
                    if ($au["codigo"] == $aux["cod_prod_nf"]){
                        $desc_aux .= "ALT => " . $au["descripcion"] . " - ";
                    }
                }
                $aux["descripcion"] = $desc_aux . $desc_;
            }
            $archivos[4][] = $aux;
        }
        foreach($archivos_c as $aux){
            $archivos[3][] = $aux;
        }
        foreach($archivos_b as $aux){
            $archivos[2][] = $aux;
        }
        foreach($archivos_a as $aux){
            $archivos[1][] = $aux;
        }  
                
        $_SESSION['n1'] = $n1;
        $_SESSION['n2'] = $n2;
        $_SESSION['n3'] = $n3;
        $_SESSION['n4'] = $n4;

        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;
        
        $destinos = $this->getDestinos();
        $archivos_destinos = $this->getArchivosDestinos();

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.template.tabla.php";
        
    }
    
    
    public function getRegistrosFiltroFilesArchivosMapa($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
                
         
        if ($opc > 0){
            if ($n1 == 0){
                $n1 = $opc;
                $n2 = $n3 = $n4 = 0;
            } elseif ($n2 == 0){
                $n2 = $opc;
                $n3 = $n4 = 0;
            } elseif ($n3 == 0){
                $n3 = $opc;
                $n4 = 0;
            } elseif ($n4 == 0){
                $n4 = $opc;
            }
        }
        /*
        $_SESSION['n1'] = $n1;
        $_SESSION['n2'] = $n2;
        $_SESSION['n3'] = $n3;
        $_SESSION['n4'] = $n4;
        $_SESSION['opc'] = $opc;*/
        
        $registros = [];
        $regs = $this->getProductosAll();
        foreach ($regs as $usu) {
            $clave = $usu["cna"] . "_" . $usu["cnb"] . "_" . $usu["cnc"] . "_" . $usu["cnd"] . "_" . $usu["cne"];
            $registros[$clave]["opciones"][] = $usu;
            $registros[$clave]["ena"] = $usu["ena"];
            $registros[$clave]["enb"] = $usu["enb"];
            $registros[$clave]["enc"] = $usu["enc"];
            $registros[$clave]["end"] = $usu["end"];
            $registros[$clave]["ene"] = $usu["ene"];
            $registros[$clave]["enf"] = $usu["enf"];
            $registros[$clave]["dna"] = $usu["dna"];
            $registros[$clave]["dnb"] = $usu["dnb"];
            $registros[$clave]["dnc"] = $usu["dnc"];
            $registros[$clave]["dnd"] = $usu["dnd"];
            $registros[$clave]["dne"] = $usu["dne"];
            $registros[$clave]["cna"] = $usu["cna"];
            $registros[$clave]["cnb"] = $usu["cnb"];
            $registros[$clave]["cnc"] = $usu["cnc"];
            $registros[$clave]["cnd"] = $usu["cnd"];
            $registros[$clave]["cne"] = $usu["cne"];
        }

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.template.mapa.php";
        
    }
    
    
    public function getRegistrosFiltroEventosOtp($orderby, $sentido, $registros, $pagina, $busqueda, $n1, $n2, $n3, $n4, $opc){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
        
        $archivos = $this->conn->getEventosOtp($opc);

        $otp = $this->conn->getOtp($opc)[0];

        //var_dump($archivos);
        
        $registros = $archivos;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/eventos.otp.template.php";
        
    }
    
    
    public function getRegistrosFiltroFilesP($orderby, $sentido, $registros, $pagina, $busqueda, $opc){
        
        $_SESSION["pagina"] = $pagina;        
        $_SESSION["cant_reg"] = $registros;        
        $_SESSION["busqueda"] = $busqueda;                
        $_SESSION['orderby'] = $orderby;        
        $_SESSION['sentido'] = $sentido;
                       
        $prod  = $this->conn->getProduccions($opc)[0];
        $prod_p = $this->getProductosP();

        $pp = "";

        foreach($prod_p as $aux){
            if ($aux["codigo"] == $prod["prod_personalizado_id"]){
                $pp = $aux["descripcion"];
            }
        }

        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files_p.busqueda.template.php";
        
    }
    
    public function addProducto($descripcion, $n1, $n2, $n3, $n4) {
        $devuelve = $this->conn->addProducto($descripcion, $n1, $n2, $n3, $n4);
        
        return $devuelve;
        
    }
    
    public function addProductoAlt($descripcion, $opcion) {
        $devuelve = $this->conn->addProductoAlt($descripcion, $opcion);
        
        return $devuelve;
        
    }
    
    public function updateProducto($codigo, $descripcion, $n1, $n2, $n3, $n4) {
        $devuelve = $this->conn->updateProducto($codigo, $descripcion, $n1, $n2, $n3, $n4);
        
        return $devuelve;
        
    }
    
    public function updateProductoAlt($codigo, $descripcion) {
        $devuelve = $this->conn->updateProductoAlt($codigo, $descripcion);
        
        return $devuelve;
        
    }
    
    public function deleteProducto($codigo, $n1, $n2, $n3, $n4) {
        $devuelve = $this->conn->deleteProducto($codigo, $n1, $n2, $n3, $n4);
        
        return $devuelve;
        
    }
    
    public function deleteProductoAlt($codigo) {
        $devuelve = $this->conn->deleteProductoAlt($codigo);
        
        return $devuelve;
        
    }
    
    public function mostrarArchivos($estado, $codigo, $nivel) {
        $devuelve = $this->conn->mostrarArchivos($estado, $codigo, $nivel);
        
        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/files.archivos.tabla.mapa.php";
        
    }
    
    public function cambiar_estadoProductoParam($estado, $codigo, $nivel) {
        $devuelve = $this->conn->cambiar_estadoProductoParam($estado, $codigo, $nivel);
        
        return $devuelve;
        
    }
    
    public function cambiar_estadoProducto($estado, $codigo, $n1, $n2, $n3, $n4) {
        $devuelve = $this->conn->cambiar_estadoProducto($estado, $codigo, $n1, $n2, $n3, $n4);
        
        return $devuelve;
        
    }
    
    public function cambiar_estadoOpcion($estado, $codigo, $n1, $n2, $n3, $n4) {
        $devuelve = $this->conn->cambiar_estadoOpcion($estado, $codigo, $n1, $n2, $n3, $n4);
        
        return $devuelve;
        
    }
    
    public function getProducto($codigo, $n1, $n2, $n3, $n4) {
        $devuelve = $this->conn->getProducto($codigo, $n1, $n2, $n3, $n4);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getOt($codigo) {
        $devuelve = $this->conn->getOt($codigo);
        
        return $devuelve[0];
        
    }
    
    public function getProductoAlt($codigo) {
        $devuelve = $this->conn->getProductoAlt($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getProductosAll() {
        $devuelve = $this->conn->getProductosAll();
        
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
    
    public function getDestinos() {
        $devuelve = $this->conn->getDestinos();
        
        return $devuelve;
        
    }
    
    public function getArchivosDestinos() {
        $devuelve = $this->conn->getArchivosDestinos();
        
        return $devuelve;
        
    }
    
    public function getUnidades() {
        $devuelve = $this->conn->getUnidades();
        
        return $devuelve;
        
    }
    
    public function getDetalles() {
        $devuelve = $this->conn->getDetalles();
        
        return $devuelve;
        
    }

    public function getOtsportada() {
        $devuelve=$this->conn->getOtsportada();

        return $devuelve;
    }

    public function getProductosBOnly($codigo) {
        $devuelve=$this->conn->getProductosBOnly($codigo);

        return json_encode($devuelve);
    }

    public function getProductosCOnly($codigo) {
        $devuelve=$this->conn->getProductosCOnly($codigo);

        return json_encode($devuelve);
    }

    public function getProductosDOnly($codigo) {
        $devuelve=$this->conn->getProductosDOnly($codigo);

        return json_encode($devuelve);
    }
}
