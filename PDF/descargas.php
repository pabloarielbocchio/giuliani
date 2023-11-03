<?php
session_start();

//include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/portada.controller.php";
//include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/planos.controller.php";
include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/ot_eventos.controller.php";

function ZipStatusString($status)
{
        switch ((int) $status) {
                case ZipArchive::ER_OK:
                        return 'No error';
                case ZipArchive::ER_MULTIDISK:
                        return 'Multi-disk zip archives not supported';
                case ZipArchive::ER_RENAME:
                        return 'Renaming temporary file failed';
                case ZipArchive::ER_CLOSE:
                        return 'Closing zip archive failed';
                case ZipArchive::ER_SEEK:
                        return 'Seek error';
                case ZipArchive::ER_READ:
                        return 'Read error';
                case ZipArchive::ER_WRITE:
                        return 'Write error';
                case ZipArchive::ER_CRC:
                        return 'CRC error';
                case ZipArchive::ER_ZIPCLOSED:
                        return 'Containing zip archive was closed';
                case ZipArchive::ER_NOENT:
                        return 'No such file';
                case ZipArchive::ER_EXISTS:
                        return 'File already exists';
                case ZipArchive::ER_OPEN:
                        return 'Can\'t open file';
                case ZipArchive::ER_TMPOPEN:
                        return 'Failure to create temporary file';
                case ZipArchive::ER_ZLIB:
                        return 'Zlib error';
                case ZipArchive::ER_MEMORY:
                        return 'Malloc failure';
                case ZipArchive::ER_CHANGED:
                        return 'Entry has been changed';
                case ZipArchive::ER_COMPNOTSUPP:
                        return 'Compression method not supported';
                case ZipArchive::ER_EOF:
                        return 'Premature EOF';
                case ZipArchive::ER_INVAL:
                        return 'Invalid argument';
                case ZipArchive::ER_NOZIP:
                        return 'Not a zip archive';
                case ZipArchive::ER_INTERNAL:
                        return 'Internal error';
                case ZipArchive::ER_INCONS:
                        return 'Zip archive inconsistent';
                case ZipArchive::ER_REMOVE:
                        return 'Can\'t remove file';
                case ZipArchive::ER_DELETED:
                        return 'Entry has been deleted';
                default:
                        return "Unknown zip status" . $status;
        }
}
if (isset($_GET["codigo"])) {
        $codigo = $_GET["codigo"];
}

//var_dump($registros);
if ($_SESSION["archivos_datos"] != null) {
        $zipname = 'archivos.zip';
        $existe = file_exists($zipname);
        $accion = $existe ? ZipArchive::OVERWRITE : ZipArchive::CREATE;
        $zip = new ZipArchive;

        $zip->open($zipname, $accion);
        if ($registrosPersonalizados != null) {
                $texto = __DIR__ . '\archivos.zip';
        } else {
                $texto =   "no hay algo";
        }
        if ($_SESSION["valorSeleccion"] == 0) {
                foreach ($_SESSION["archivos_datos"] as $file) {

                        //  echo "../".$file["ruta"];

                        $zip->addFile("../" . $file["ruta"], basename($file["ruta"]));


                        // echo basename($file["ruta"]);
                }
        } else {
                foreach ($_SESSION["archivos_datos"] as $file) {

                        //  echo "../".$file["ruta"];

                        $zip->addFile("../" . $file["ruta"], basename($file["ruta"]));


                        // echo basename($file["ruta"]);
                }
        }

        $zip->close();
        $result = $zip->status;
        ob_end_clean();

        try {
                header('Content-Type: application/zip');
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-disposition: attachment; filename=' . $zipname);
                header('Content-Length: ' . filesize($zipname));

                readfile($zipname);
        } catch (Exception $th) {
                echo $th;
        }
        $filesEliminar = glob(__DIR__ . "/" . $zipname);
        foreach ($filesEliminar as $files1) {

                $valore = unlink($files1); //elimino el fichero

        }

        $controlador = Ot_eventosController::singleton_ot_eventos();
        $observaciones = "Descarga Archivo comprimido IDPROD #". $_SESSION["zip_otp"];
        $controlador->addOt_evento($_SESSION["zip_otd"], $_SESSION["zip_otp"], 13, 0, $observaciones);
}
?>
<html>

<body>
        <h1>
                <?php echo $_SESSION["valorCodigoPP"]; ?>
        </h1>
</body>

</html>