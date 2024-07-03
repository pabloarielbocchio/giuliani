<!DOCTYPE html>
<?php
	if (isset($_GET["codigo"])){
        $codigo = $_GET["codigo"];    
        include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/planos.controller.php";
        $controlador = PlanosController::singleton_planos();
        $prod_a = $controlador->getProductosA();
        $prod_b = $controlador->getProductosB();
        $prod_c = $controlador->getProductosC();
        $prod_d = $controlador->getProductosD();
        $prod_e = $controlador->getProductosE();
        $prod_f = $controlador->getProductosF();
        $prod_p = $controlador->getProductosP();
        $prod_s = $controlador->getProductosS();
        $prod_x = $controlador->getProductosX();        
        $archivos = $controlador->getArchivosAll();
        $devuelve = $controlador->getOtp($codigo);
        $registros = [];              

        foreach($devuelve as $dev){

            $seccion = $dev["seccion"];
            $sector = $dev["sector"];
            $item_vendido = $dev["item_vendido"];
            $observaciones = $dev["observaciones"];
            $ot = $dev["orden_trabajo_id"];
            $cliente = $dev["cliente"];

            $ot_detalle_id = $dev["ot_detalle_id"];
            $prod_estandar_id = $dev["prod_estandar_id"];
            $prod_personalizado_id = $dev["prod_personalizado_id"];

            $cod_prod_nf = -1;
            $cod_prod_ne = -1;
            $cod_prod_nd = -1;
            $cod_prod_nc = -1;
            $cod_prod_nb = -1;
            $cod_prod_na = -1;

            foreach($prod_x as $aux){
                if ($aux["prod_standar_id"] == $prod_estandar_id){
                    $cod_prod_nf[] = $aux["prod_f_id"];
                    break;
                }
            }

            foreach($prod_s as $aux){
                if ($aux["codigo"] == $prod_estandar_id){
                    $cod_prod_nd = $aux["cod_prod_nd"];
                    break;
                }
            }

            foreach($prod_d as $aux){
                if ($aux["codigo"] == $cod_prod_nd){
                    $cod_prod_nc = $aux["cod_prod_nc"];
                    break;
                }
            }

            foreach($prod_c as $aux){
                if ($aux["codigo"] == $cod_prod_nc){
                    $cod_prod_nb = $aux["cod_prod_nb"];
                    break;
                }
            }

            foreach($prod_b as $aux){
                if ($aux["codigo"] == $cod_prod_nb){
                    $cod_prod_na = $aux["cod_prod_na"];
                    break;
                }
            }
        }

        foreach($archivos as $aux){
            if ($aux["cod_prod_na"] == $cod_prod_na){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_nb"] == $cod_prod_nb){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_nc"] == $cod_prod_nc){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_nd"] == $cod_prod_nd){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_ne"] == $cod_prod_ne){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_prod_personalizado_id"] == $prod_personalizado_id){
                $registros[$aux["codigo"]] = $aux;

                
            }
            if ($aux["cod_prod_estandar_id"] == $prod_estandar_id){
                $registros[$aux["codigo"]] = $aux;
            }
            if ($aux["cod_ot_detalle_id"] == $ot_detalle_id){
                $registros[$aux["codigo"]] = $aux;
            }
            foreach($cod_prod_nf as $f){
                if ($aux["cod_prod_nf"] == $f){
                    $registros[$aux["codigo"]] = $aux;
                }
            }
        }

	}
?>
<html lang="en">
    <head>
        <link href="css/styles.css" rel="stylesheet" />
    </head>

    <style type="text/css">

    table { vertical-align: top; }
    tr    { vertical-align: top; }
    td    { vertical-align: top; }

    .midnight-blue{
        background:#090c35;
        padding: 4px 4px 4px;
        color:white;
        font-weight:bold;
        font-size:12px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .silver{
        background:white;
        padding: 3px 4px 3px;
        border: solid 1px #000000;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .clouds{
        background:#ecf0f1;
        padding: 3px 4px 3px;
        border: solid 1px #000000;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .border-top{
        border-top: solid 1px #bdc3c7;
        
    }

    .border-left{
        border-left: solid 1px #bdc3c7;
    }

    .border-right{
        border-right: solid 1px #bdc3c7;
    }

    .border-bottom{
        border-bottom: solid 1px #bdc3c7;
    }

    .recuadro{
        padding: 3px 4px 3px;
        border: solid 1px #000000;
    }

    .izquierda{
        border-right: solid 1px #ffffff;
    }

    .derecho{
        border-right: solid 1px #ffffff;
    }

    .derecho1{
        border-right: solid 2px #000000;
    }

    table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
    

    #tabla3{
    border: 1px solid #80A93E;
    width: 200px;
    }

    .mostaza{
    background-color: #fdd83f;
    font-weight: bold;
    font-size: 8pt;
    padding: 2 2 2 20px;
    height: 20px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .griso{
    background-color: #606060;
    font-weight: bold;
    font-size: 8pt;
    padding: 2 2 2 20px;
    height: 20px;
    width: 65%;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .grisclaro{
    background-color: #DCDCDC;
    font-weight: bold;
    font-size: 8pt;
    padding: 2 2 2 20px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .grispie{
    background-color: #EEEEEE;
    font-weight: bold;
    font-size: 8pt;
    padding: 2 2 2 20px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .musgo{
    background-color: #006F73;
    font-weight: bold;
    font-size: 8pt;
    padding: 0 0 0 0px;
    height: 20px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    .blanco{
    background-color: #FFFFFF;
    font-weight: bold;
    font-size: 8pt;
    padding: 2 2 2 20px;
    height: 20px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    p.round3 {
    border: 10px solid #006F73;
    border-radius: 0 0 0 32px;
    background-color: #006F73;
    padding: 0 0 0 0px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    p.recto {
    border: 5px solid #006F73;
    background-color: #006F73;
    padding: 0 0 0 0px;
  -webkit-appearance: none;
     -moz-appearance: none;
          appearance: none;
  -webkit-print-color-adjust: exact;
          color-adjust: exact;
    }

    </style>
    <body id="page-top">
        <page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial" >
        
                <div style="text-align: center;">
                    <h1 class="logo-name">
                        <?php $imagen = "../imagenes/logo.png"; ?>
                        <img width="300px" src="<?php echo $imagen; ?>" >
                    </h1>
                </div>

            <legend style="text-align: center;">Planimetría</legend>
            
            <br />
        
            <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
                <tr>
                    <th style="width: 10%;text-align:right;" class='midnight-blue'>OV#/PY:</th>
                    <th style="width: 10%;text-align:left;" class='midnight-blue'><?php echo $ot; ?></th>
                    <th style="width: 15%;text-align:right;" class='midnight-blue'>CLIENTE:</th>
                    <th style="width: 65%;text-align:left;" class='midnight-blue'><?php echo $cliente; ?></th>
                </tr>
            </table>

            <br />

            <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
                <tr>
                    <th style="width: 20%;text-align:right;" class='midnight-blue'>SECCIÓN:</th>
                    <th style="width: 80%;text-align:left;" class='midnight-blue'><?php echo $seccion; ?></th>
                </tr>
                <tr>
                    <th style="width: 20%;text-align:right;" class='midnight-blue'>SECTOR:</th>
                    <th style="width: 80%;text-align:left;" class='midnight-blue'><?php echo $sector; ?></th>
                </tr>
                <tr>
                    <th style="width: 20%;text-align:right;" class='midnight-blue'>EQUIPO:</th>
                    <th style="width: 80%;text-align:left;" class='midnight-blue'><?php echo $item_vendido; ?></th>
                </tr>
                <tr>
                    <th style="width: 20%;text-align:right;" class='midnight-blue'>OBSERVACIÓN:</th>
                    <th style="width: 80%;text-align:left;" class='midnight-blue'><?php echo $observaciones; ?></th>
                </tr>
            </table>

            <br />
        
            <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
                <tr>
                    <th style="width: 10%;text-align:center;" class='midnight-blue'>ID-PLANO</th>
                    <th style="width: 80%;text-align:center;" class='midnight-blue'>DESCRIPCIÓN</th>
                    <th style="width: 10%;text-align:center;" class='midnight-blue'>ACTIVO</th>
                </tr>
                <?php foreach ($registros as $usu) { if ($usu["activo"] != 1) continue;?>
                    <tr style="height: 27px;">
                        <td style="width: 10%; text-align: center;"><?php echo $usu["codigo"]; ?></td>
                        <td style="width: 80%; text-align: left;"><?php echo $usu["descripcion"]; ?></td>
                        <td style="width: 10%; text-align: center;"><?php echo $usu["activo"] == 1 ? "SI" : "NO"; ?></td>
                    </tr>
                <?php }?>            
            </table>
            
            <br />

            <legend style="text-align: center; font-size: 12px;">USO EXCLUSIVO POR GIULIANI HERMANOS SA - PROHIBIDA SU REPRODUCCION SIN AUTORIZACION</legend>
            
        </page>

        <script type="text/javascript"> window.print(); </script>

    </body>
</html>