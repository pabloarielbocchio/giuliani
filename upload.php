<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT ^ E_DEPRECATED);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

include $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";

$tipo = $_GET["file"];

$continuar = false;

class UploadModel {
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

    public static function singleton_upload() {
        if ( !isset( self::$instancia ) ) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    
    public function getSQL($sql){
        try {
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll();
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
}

if (!$_FILES['fileToUpload']['error']) {
    $new_file_name = $_SERVER['DOCUMENT_ROOT'] . '/Giuliani/uploads/' . $tipo . '.xls'; //rename file
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $new_file_name)) {
        $continuar = true;
    }
}

$model = new UploadModel();

if ($continuar) {

    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT ^ E_DEPRECATED);
    ini_set('max_execution_time', 300);
    $Filepath = $_SERVER['DOCUMENT_ROOT'] . '/Giuliani/uploads/nueva_ot.xls';
    require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
    require('spreadsheet-reader-master/SpreadsheetReader.php');
    $cod_ot = intval($_SESSION['ot']);

    try {
        $Spreadsheet = new SpreadsheetReader($Filepath);
        $BaseMem = memory_get_usage();
        $Sheets = $Spreadsheet->Sheets();
        $cuenta = 0;
        $_tipo = $tipo;
        foreach ($Sheets as $Index => $Name) {
            $Time = microtime(true);
            $Spreadsheet->ChangeSheet($Index);
            $Spreadsheet->ChangeSheet(0);
            $cuenta = 0;
            foreach ($Spreadsheet as $Key => $Row) {
                if ($cuenta > 0){
                    $sql = "INSERT INTO orden_trabajos_detalles (orden_trabajo_id, seccion, sector, estado_id, prioridad_id, item_vendido, observaciones, cantidad, pu, pu_cant, pu_neto, clasificacion, usuario_m, fecha_m) "
                    . "VALUES (" . $cod_ot . ",'" . htmlspecialchars(utf8_encode(trim($Row[3]))) . "','" . htmlspecialchars(utf8_encode(trim($Row[4]))) . "', 1, 3, '" . htmlspecialchars(utf8_encode($Row[5])) . "', '" . htmlspecialchars(utf8_encode($Row[6])) . "', " . intval($Row[7]) . ", " . floatval($Row[8]) . ",  " . floatval($Row[9]) . ",  " . floatval($Row[10]) . ", '" . htmlspecialchars(utf8_encode($Row[11])) . "', '" . $_SESSION["usuario"] . "', now());";
                    /*var_dump($sql);
                    echo "<br />";
                    echo "<br />";*/
                    $devuelve = $model->getSQL($sql);
                }
                $cuenta++;
            }
        }
        header('Location: detalles.php?cod_ot=' . $cod_ot);
    } catch (Exception $E) {
        echo $E->getMessage();
    }
}
?>