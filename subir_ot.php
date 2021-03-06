<?php
    session_start();
    $file = $_FILES;
    $data = $_POST;

    $otp = $data["otp"];
    $fileName = $file['file']['name'];
    
    $respuesta = '';

    if ($data) {
        if (!$file['file']['name']) {
            $respuesta = [
                'content' => $file,
                'status' => 'error',
                'message' => 'Seleccione un archivo a subir.'
            ];
        }
       
        if (!$respuesta) {
            $uploadPath = 'uploads/';
            $uploadPath .= "ot_" . $otp . '/';
            (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
            //$fileName = $otp . "_" . $fileName;

            $_fileNameTmp = $file['file']['tmp_name'];
            $existia_antes = 0;            
            if (file_exists($uploadPath . $fileName)){
                //$existia_antes = 1;
                $fileName = date("YmdHis") . "_" . $fileName;
            }
            $move_upload = move_uploaded_file($_fileNameTmp, $uploadPath . $fileName);
            $observaciones = $uploadPath;
            if (!$move_upload) {
                $respuesta = [
                    'content' => $file,
                    'status' => 'error',
                    'message' => 'No se pudo subir la imagen. Por favor, intentelo nuevamente.'
                ];
            } else {
                if ($existia_antes == 0) {
                    include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/archivos.controller.php";
                    $controlador = ArchivosController::singleton_archivos();
                    $fecha_hora = date("Y-m-d H:i:s");
                    $activo = 1;
                    
                    $controlador->addArchivo($fileName, $uploadPath . $fileName, $fecha_hora, $activo, null, null, null, null, null, null, null, null, null, $otp);
                    $ultimo = $controlador->getLastArchivo()[0]["codigo"];
                    $controlador->addArchivo_produccion($ultimo, null, "", null, $otp);
                    
                }
            }                      
        }
       
    }
    var_dump($respuesta);
?>
