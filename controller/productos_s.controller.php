<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda']);
}

function addProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->addProducto  (    
                                        $_POST['descripcion'],
                                        $_POST['prodd']
            );
}

function updateProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->updateProducto(    $_POST['codigo'], 
                                        $_POST['descripcion'],
                                        $_POST['prodd']
            );
}

function updateProductoAll() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->updateProductoAll(    $_POST['codigo'], 
                                        $_POST['descripcion'],
                                        $_POST['configuracion'],
                                        $_POST['otd'],
                                        $_POST['prodd'],
                                        $_POST['cantidad'],
                                        $_POST['unidad']
            );
}

function deleteProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->deleteProducto($_POST['codigo']);
}

function getProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->getProducto($_POST['codigo']);
}

class ProductosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/productos_s.model.php";
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

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;
        
        $prod_d = $this->getProductosD();

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/productos_s.busqueda.template.php";
        
    }
    
    public function addProducto($descripcion, $prodd) {
        $devuelve = $this->conn->addProducto($descripcion, $prodd);
        
        return $devuelve;
        
    }
    
    public function updateProducto($codigo, $descripcion, $prodd) {
        $devuelve = $this->conn->updateProducto($codigo, $descripcion, $prodd);
        
        return $devuelve;
        
    }
    
    public function updateProductoAll($codigo, $descripcion, $configuracion, $otd, $prodd, $cantidad, $unidad) {
        
        $devuelve = $this->conn->addProducto($descripcion, $prodd);
        if ($devuelve == 0){
            $ultimo = $this->conn->getLastProducto()[0]["codigo"];
            $observaciones = "";
            $prioridad = 1;
            $devuelve = $this->conn->addOt_produccion(0, $cantidad, 1, $ultimo, 0, $unidad, 1, $prioridad, $otd, $observaciones);
            $ultimo_otp = $this->conn->getLastOtp()[0]["codigo"];
            foreach($configuracion as $conf){
                $this->addProductoConf($conf, $ultimo); // $ultimo es el prod_estandar
            }
            $nd = $this->conn->getProductosTabla("productos_estandar", $ultimo)["cod_prod_nd"];
            $nc = $this->conn->getProductosTabla("productos_nivel_d", $nd)["cod_prod_nc"];
            $nb = $this->conn->getProductosTabla("productos_nivel_c", $nc)["cod_prod_nb"];
            $na = $this->conn->getProductosTabla("productos_nivel_b", $nb)["cod_prod_na"];

            

            $archivos_na = $this->conn->getProductosArchivos("cod_prod_na", $na, " and cod_prod_nb = 0 and cod_prod_nc = 0 and cod_prod_nd = 0 ");
            $archivos_nb = $this->conn->getProductosArchivos("cod_prod_nb", $nb, " and cod_prod_nc = 0 and cod_prod_nd = 0 ");
            $archivos_nc = $this->conn->getProductosArchivos("cod_prod_nc", $nc, " and cod_prod_nd = 0 ");
            $archivos_nd = $this->conn->getProductosArchivos("cod_prod_nd", $nd, " ");

            
           
            foreach($archivos_na as $aux){
                $observaciones = "";
                $this->conn->addArchivo_produccion($aux["codigo"], $ultimo_otp, $observaciones);
            }
            foreach($archivos_nb as $aux){
                $observaciones = "";
                $this->conn->addArchivo_produccion($aux["codigo"], $ultimo_otp, $observaciones);
            }
            foreach($archivos_nc as $aux){
                $observaciones = "";
                $this->conn->addArchivo_produccion($aux["codigo"], $ultimo_otp, $observaciones);
            }
            foreach($archivos_nd as $aux){
                $observaciones = "";
                $this->conn->addArchivo_produccion($aux["codigo"], $ultimo_otp, $observaciones);
            }

            unset($_SESSION['n1']);
            unset($_SESSION['n2']);
            unset($_SESSION['n3']);
            unset($_SESSION['n4']);

            
            $observaciones = "Actualizacion Estado OT Produccion, ingreso a Ingenieria. " . $descripcion;
            $this->conn->addOt_estado($ultimo_otp, 1, null, $observaciones, 'ingenieria');

            // Faltan los archivos
        }
        
        return $devuelve;
        
    }
    
    public function deleteProducto($codigo) {
        $devuelve = $this->conn->deleteProducto($codigo);
        
        return $devuelve;
        
    }
    
    public function getProducto($codigo) {
        $devuelve = $this->conn->getProducto($codigo);
        
        return json_encode($devuelve[0]);
        
    }
    
    public function getUnidades() {
        $devuelve = $this->conn->getUnidades();
        
        return $devuelve;
        
    }
    
    public function getProductosD() {
        $devuelve = $this->conn->getProductosD();
        
        return $devuelve;
        
    }
    
    public function addProductoConf($conf, $ultimo) {
        $devuelve = $this->conn->addProductoConf($conf, $ultimo);
        
        return $devuelve;
        
    }
}
