<?php
    session_start();
    $file = $_FILES;
    $data = $_POST;

    $n1 = $data["n1"];
    $n2 = $data["n2"];
    $n3 = $data["n3"];
    $n4 = $data["n4"];
    $n5 = $data["n5"];
    $n6 = $data["n6"];
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
            if ($n1 > 0){
                $uploadPath .= $n1 . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $n1 . "_" . $fileName;
            }
            if ($n2 > 0){
                $uploadPath .= $n2 . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $n2 . "_" . $fileName;
            }
            if ($n3 > 0){
                $uploadPath .= $n3 . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $n3 . "_" . $fileName;
            }
            if ($n4 > 0){
                $uploadPath .= $n4 . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $n4 . "_" . $fileName;
            }
            if ($n5 > 0){
                $uploadPath .= $n5 . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $n5 . "_" . $fileName;
            }
            if ($n6 > 0){
                $uploadPath .= $n6 . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $n6 . "_" . $fileName;
            }

            $_fileNameTmp = $file['file']['tmp_name'];
            $existia_antes = 0;            
            if (file_exists($uploadPath . $fileName)){
                //$existia_antes = 1;
                $fileName = "_" . date("YmdHis") . $fileName;
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
                    $controlador->addArchivo($fileName, $uploadPath . $fileName, $fecha_hora, $activo, $n1, $n2, $n3, $n4, $n5, $n6, null, null, null);
                }
            }                      
        }
       
    }
    var_dump($respuesta);
?>
