<?php
$html = '';
$conexion = new mysqli('localhost','root','', 'giuliani');
session_start();

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";
class Conectar{
    public $conn;
    public function __construct()

    {
        try {
            $this->conn = Conexion::singleton_conexion();
            return $this;
        } catch (Exception $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
}


    // public function __construct() {
    //     try {                
    //         $this->conn = Conexion::singleton_conexion();
    //     } catch ( Exception $e ) {
    //         $error = "Error!: " . $e->getMessage();
    //         return $error;
    //     }
    // }  
$id_category = $_POST['id_category'];

$result = $conexion->query(
    "SELECT DISTINCT codigo, item_vendido FROM orden_trabajos_detalles WHERE orden_trabajo_id = " . intval($id_category) . ";"
);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {                
        $html .= '<option value="'.$row['codigo'].'">'.$row['item_vendido'].'</option>';
    }
}
echo $html;
?>