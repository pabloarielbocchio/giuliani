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
                                        $_POST['oracle'],
                                        $_POST['unidad']
            );
}

function updateProducto() {
    $controlador = ProductosController::singleton_productos();
    
    echo $controlador->updateProducto(    $_POST['codigo'], 
                                        $_POST['descripcion'],
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
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/productos_p.model.php";
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
        
        $unidades = $this->getUnidades();

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/productos_p.busqueda.template.php";
        
    }
    
    public function addProducto($descripcion, $oracle, $unidad) {
        $devuelve = $this->conn->addProducto($descripcion, $oracle, $unidad);
        
        return $devuelve;
        
    }
    
    public function updateProducto($codigo, $descripcion, $unidad) {
        $devuelve = $this->conn->updateProducto($codigo, $descripcion, $unidad);
        
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
}
