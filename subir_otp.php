<?php
    session_start();
    $file = $_FILES;
    $data = $_POST;

    $otp = $data["otp"];
    $standar = $data["standar"];
    $prod_std = $data["prod_std"];
    $prod_perso = $data["prod_perso"];
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
            if ($standar > 0){
                $uploadPath .= "prod_std_" . $prod_std . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $prod_std . "_" . $fileName;
            } else {
                $uploadPath .= "prod_perso_" . $prod_perso . '/';
                (!file_exists($uploadPath)) && mkdir($uploadPath, 0755, true);
                $fileName = $prod_perso . "_" . $fileName;
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
                    if ($standar > 0){
                        $controlador->addArchivo($fileName, $uploadPath . $fileName, $fecha_hora, $activo, null, null, null, null, null, null, null, $prod_std, null);
                        $ultimo = $controlador->getLastArchivo()[0]["codigo"];
                    } else {
                        $controlador->addArchivo($fileName, $uploadPath . $fileName, $fecha_hora, $activo, null, null, null, null, null, null, $prod_perso, null, null);
                        $ultimo = $controlador->getLastArchivo()[0]["codigo"];
                    }
                    //buscar el ultimo archivo insertado
                    $controlador->addArchivo_produccion($ultimo, $otp, "");
                    $destinos = $controlador->getDestinos();
                    foreach($destinos as $d){
                        //$controlador->addArchivo_destino($ultimo, $d["codigo"], "");
                    }
                }
            }                      
        }
       
    }
    var_dump($respuesta);
?>
