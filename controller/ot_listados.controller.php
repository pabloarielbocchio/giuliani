<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

if (isset($_POST['funcion'])) {
    if (function_exists($_POST['funcion'])) {
        $_POST['funcion']();
    }
}

function getRegistrosFiltro(){
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->getRegistrosFiltro($_POST['orderby'], $_POST['sentido'], $_POST['registros'], $_POST['pagina'], $_POST['busqueda'], $_POST['estado']);
}

function addOt_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->addOt_listado  (    
                                        $_POST['nroserie'],
                                        $_POST['cliente'],
                                        $_POST['prioridad'],
                                        $_POST['fecha'],
                                        $_POST['entrega'],
                                        $_POST['observaciones'],
                                        $_POST['tipo']
            );
}

function updateOt_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->updateOt_listado(    $_POST['codigo'], 
                                            $_POST['nroserie'],
                                            $_POST['cliente'],
                                            $_POST['prioridad'],
                                            $_POST['fecha'],
                                            $_POST['entrega'],
                                            $_POST['observaciones'],
                                            $_POST['tipo']
            );
}

function estadoOt_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->estadoOt_listado(    $_POST['codigo'], 
                                            $_POST['estado'],
                                            $_POST['avance']
            );
}

function estadoOt_listado_all() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->estadoOt_listado_all(    $_POST['codigo'], 
                                            $_POST['estadoing'],
                                            $_POST['estadoprod'],
                                            $_POST['estadodespacho']
            );
}

function abrirOt_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->abrirOt_listado(    $_POST['codigo']
            );
}

function anclarOt() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->anclarOt(    
                $_POST['codigo'],
                $_POST['anclada']
            );
}

function finalizarOt_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->finalizarOt_listado(    $_POST['codigo']
            );
}

function abrirOtDetalle_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->abrirOtDetalle_listado(    $_POST['codigo']
            );
}

function finalizarOtDetalle_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->finalizarOtDetalle_listado(    $_POST['codigo']
            );
}

function deleteOt_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->deleteOt_listado($_POST['codigo']);
}

function getOt_listado() {
    $controlador = Ot_listadosController::singleton_ot_listados();
    
    echo $controlador->getOt_listado($_POST['codigo']);
}

class Ot_listadosController {

    private static $instancia;
    private $conn;

    public function __construct() {
        try {
            include $_SERVER['DOCUMENT_ROOT']."/Giuliani/model/ot_listados.model.php";
            $this->conn = Ot_listadosModel::singleton_ot_listados();
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }

    public static function singleton_ot_listados() {
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getCountOt_listados(){
        return intval($this->conn->getCountOt_listados()[0]);
        
    }

    
    public function getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $estado){
        
        $_SESSION["pagina"] = $pagina;
        
        $_SESSION["cant_reg"] = $registros;
        
        $_SESSION["busqueda"] = $busqueda;
                
        $_SESSION['orderby'] = $orderby;
        
        $_SESSION['sentido'] = $sentido;
        
        $_SESSION['tipo_selected'] = $estado;
        
        $devuelve = $this->conn->getRegistrosFiltro($orderby, $sentido, $registros, $pagina, $busqueda, $estado);
                                
        $registros = $devuelve;
        
        $_SESSION['registros'] = $registros;

        include $_SERVER['DOCUMENT_ROOT']."/Giuliani/templates/ot_listados.busqueda.template.php";
        
    }
    
    public function addOt_listado($nroserie, $cliente, $prioridad, $fecha, $entrega, $observaciones, $tipo) {
        $devuelve = $this->conn->addOt_listado($nroserie, $cliente, $prioridad, $fecha, $entrega, $observaciones, $tipo);
        
        return $devuelve;
        
    }
    
    public function abrirOt_listado($codigo) {
        $devuelve = $this->conn->abrirOt_listado($codigo);
        
        return $devuelve;
        
    }
    
    public function anclarOt($codigo, $anclada) {
        $devuelve = $this->conn->anclarOt($codigo, $anclada);
        
        return $devuelve;
        
    }
    
    public function finalizarOt_listado($codigo) {
        $devuelve = $this->conn->finalizarOt_listado($codigo);
        
        return $devuelve;
        
    }
    
    public function abrirOtDetalle_listado($codigo) {
        $item = $this->conn->getOt_listado($codigo)[0];
        $devuelve = $this->conn->abrirOtDetalle_listado($codigo);
        if ($devuelve === 0){
            $ult_detalle = $codigo;
            if ($ult_detalle > 0){
                $evento = 6;
                $observaciones = "Reapertura Item Vendido " . $item["item_vendido"];
                $this->conn->addOt_evento($ult_detalle, 0, $evento, 0, $observaciones);
            }
        }        
        return $devuelve;
        
    }
    
    public function finalizarOtDetalle_listado($codigo) {
        $item = $this->conn->getOt_listado($codigo)[0];
        $devuelve = $this->conn->finalizarOtDetalle_listado($codigo);
        if ($devuelve === 0){
            $ult_detalle = $codigo;
            if ($ult_detalle > 0){
                $evento = 6;
                $observaciones = "Finalizacion Item Vendido " . $item["item_vendido"];
                $this->conn->addOt_evento($ult_detalle, 0, $evento, 0, $observaciones);
            }
        }        
        return $devuelve;
        
    }
    
    public function updateOt_listado($codigo, $nroserie, $cliente, $prioridad, $fecha, $entrega, $observaciones, $tipo) {
        $devuelve = $this->conn->updateOt_listado($codigo, $nroserie, $cliente, $prioridad, $fecha, $entrega, $observaciones, $tipo);
        
        return $devuelve;
        
    }
    
    public function estadoOt_listado($codigo, $estado, $avance) {
        $devuelve = $this->conn->estadoOt_listado($codigo, $estado, $avance);
        
        return $devuelve;
        
    }
    
    public function estadoOt_listado_all($codigo, $estadoing, $estadoprod, $estadodespacho) {
        $devuelve = $this->conn->estadoOt_listado_all($codigo, $estadoing, $estadoprod, $estadodespacho);
        
        return $devuelve;
        
    }
    
    public function deleteOt_listado($codigo) {
        $devuelve = $this->conn->deleteOt_listado($codigo);
        
        return $devuelve;
        
    }
    
    public function getOt_listado($codigo) {
        $devuelve = $this->conn->getOt_listado($codigo);
        
        return json_encode($devuelve[0]);
        
    }

    public function getPrioridades() {
        $devuelve = $this->conn->getPrioridades();
        
        return $devuelve;
        
    }

    public function getTipos() {
        $devuelve = $this->conn->getTipos();
        
        return $devuelve;
        
    }
}
