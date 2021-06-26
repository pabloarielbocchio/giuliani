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
                                        $_POST['prodf'],
                                        $_POST['prods']
            );
}

function updateProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->updateProducto(    $_POST['codigo'], 
                                        $_POST['prodf'],
                                        $_POST['prods']
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
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/productos_x.model.php";
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
        
        $prod_f = $this->getProductosF();
        $prod_s = $this->getProductosS();

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/productos_x.busqueda.template.php";
        
    }
    
    public function addProducto($prodf, $prods) {
        $devuelve = $this->conn->addProducto($prodf, $prods);
        
        return $devuelve;
        
    }
    
    public function updateProducto($codigo, $prodf, $prods) {
        $devuelve = $this->conn->updateProducto($codigo, $prodf, $prods);
        
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
    
    public function getProductosF() {
        $devuelve = $this->conn->getProductosF();
        
        return $devuelve;
        
    }
    
    public function getProductosS() {
        $devuelve = $this->conn->getProductosS();
        
        return $devuelve;
        
    }
}
