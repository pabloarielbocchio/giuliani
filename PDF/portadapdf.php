<!DOCTYPE html>
<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/portada.controller.php";
include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/planos.controller.php";

if (isset($_GET["codigo"])) {
    $codigo = $_GET["codigo"];
    $valorCodigoPP = $_GET["valorCodigoPP"];
    $_SESSION["valorCodigoPP"] = $valorCodigoPP;
    $arregloarchivos;
    $controlador2 = PortadaController::singleton_portada();
    $otp =  $controlador2->getOtp($_SESSION["valorCodigoPP"])[0];

    if ($otp["prod_personalizado_id"] > 0){
        $devolverDetalle =  $controlador2->obtenerCampossectoressecciones($otp["prod_personalizado_id"], $_SESSION["valorSeleccion"]);
        $devolverDetalleproducto = $controlador2->productoDetalle($otp["prod_personalizado_id"], $_SESSION["valorSeleccion"]);
    } else {
        $devolverDetalle =  $controlador2->obtenerCampossectoressecciones($otp["prod_estandar_id"], $_SESSION["valorSeleccion"]);
        $devolverDetalleproducto = $controlador2->productoDetalle($otp["prod_estandar_id"], $_SESSION["valorSeleccion"]);
        //var_dump($devolverDetalle);
    }

    $devuelve = $controlador2->obtenerCamposprimerosdetalles($codigo);
    foreach ($devolverDetalle as $devDetalle) {
        $seccion = $devDetalle["seccion"];
        $sector = $devDetalle["sector"];
        $equipo = $devDetalle["item"];
        $cod_pers = $devDetalle["cod_pers"];
        $id_detalle = $devDetalle["id_detalle"];
        $observacion = $devDetalle["observaciones"];
        $prodestandarid = $devDetalle["cod_estandar"];
        $prodPersonalizadoid = $devDetalle["cod_prod_personalizado"];
    }

    foreach ($devuelve as $dev) {
        $codigoOt = $dev["codigo"];
        $codigo_cliente = $dev["nro_serie"];
        $cliente = $dev["cliente"];
        $otUsuario = $dev["usuario_m"];
        $otFecha_entrega = $dev["fecha_entrega"];
    }
    foreach ($devolverDetalleproducto as $devProd) {
        $id_prod = $devProd["codigo"];
        $id_prod = $_SESSION["valorCodigoPP"];
        $descripcion = $devProd["descripcion"];
        $cantidad = $devProd["cantidad"];
    }
    if ($_SESSION["valorSeleccion"] == 0) {
        $archivos = $controlador2->getArchivosAll();
        $registros = [];
        foreach ($archivos as $aux) {
            // echo $prodestandarid;
            // echo $aux["cod_prod_estandar_id"] . "a";
            if ($aux["cod_prod_estandar_id"] == $prodestandarid) {
                $registros[$aux["codigo"]] = $aux;
                //echo $registros[$aux["codigo"]] . "aa";
            }
            if ($aux["cod_prod_personalizado_id"] == $prodPersonalizadoid) {
                $registros[$aux["codigo"]] = $aux;
                // echo $registros . "a";
            }
        }
        $arregloarchivos = $registros;
    } else {

        $arregloarchivos = $_SESSION["archivos_datos"];
    }
}
?>
<html lang="en">

<head>
    <link href="css/styles.css" rel="stylesheet" />
</head>

<style type="text/css">
    table {
        vertical-align: top;
    }

    .filatituloOt {
        width: 20%;
        width: 20%;
        font-size: 1.25rem;
        color: black;
        position: relative;
        left: 30%;
        vertical-align: middle;
    }

    .filatextoOt {
        width: 80%;
        font-size: 1.25rem;
        color: black;
        position: relative;
        right: 30%;
        vertical-align: middle;
    }

    .filatituloCliente {
        font-size: 1.25rem;
        color: black;
        text-align: center;
        position: relative;
        right: 40px;
    }

    .filatextoCliente {
        width: 80%;
        font-size: 1.25rem;
        color: black;
        position: relative;
        right: 40px;
    }

    tr {
        vertical-align: top;
    }

    td {
        vertical-align: top;
    }

    .midnight-blue {
        background: #090c35;
        padding: 4px 4px 4px;
        color: white;
        font-weight: bold;
        font-size: 12px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .silver {
        background: white;
        padding: 3px 4px 3px;
        border: solid 1px #000000;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .clouds {
        background: #ecf0f1;
        padding: 3px 4px 3px;
        border: solid 1px #000000;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .border-top {
        border-top: solid 1px #bdc3c7;

    }

    .border-left {
        border-left: solid 1px #bdc3c7;
    }

    .border-right {
        border-right: solid 1px #bdc3c7;
    }

    .border-bottom {
        border-bottom: solid 1px #bdc3c7;
    }

    .recuadro {
        padding: 3px 4px 3px;
        border: solid 1px #000000;
    }

    .izquierda {
        border-right: solid 1px #ffffff;
    }

    .derecho {
        border-right: solid 1px #ffffff;
    }

    .derecho1 {
        border-right: solid 2px #000000;
    }

    table.page_footer {
        width: 100%;
        border: none;
        background-color: white;
        padding: 2mm;
        border-collapse: collapse;
        border: none;
    }


    #tabla3 {
        border: 1px solid #80A93E;
        width: 200px;
    }

    .mostaza {
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

    .griso {
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

    .grisclaro {
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

    .grispie {
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

    .musgo {
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

    .blanco {
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

<body>
    <page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 12pt; font-family: arial">


        <div style="text-align: center;">
            <h1 class="logo-name">
                <?php $imagen = "../imagenes/giuliani_logo1.png"; ?>
                <img width="300px" src="<?php echo $imagen; ?>">
            </h1>

        </div>


        <div style=" border-right: 1px black solid;border-left: 1px black solid;height: 385px;">
            <h1 style=" font-size: 2.2rem; text-align: center;border-top: 2px black solid;border-bottom: 2px black solid;font-weight: 600; color: black;">PLANIMETRIA</h1>

            <div style="display: flex; margin-top: -10px;">
                <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt;">
                    <tr>
                        <th class="filatituloOt">
                            OV#/PY:
                        </th>
                        <th class="filatextoOt"><?php echo $codigo_cliente; ?></th>

                    </tr>
                    <tr>
                        <th style="width:80%;font-size: 0.85rem;font-weight: 700; color: black;text-align: right;">FECHA IMPRESIÓN:</th>
                        <th style="width:20%;font-size: 0.85rem;color: black;text-align: left;"><?php echo date("d/m/Y"); ?></th>
                    </tr>

                </table>
                <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt; margin-left: 20px;">
                    <tr>
                        <th class="filatituloCliente">
                            CLIENTE:
                        </th>
                        <th class="filatextoCliente"><?php echo $cliente; ?></th>
                    </tr>
                    <tr>
                        <th style="font-size: 0.85rem; color: black">
                            IMPRIMIÓ:
                        </th>
                        <th style="width: 80%;font-size: 0.85rem;color: black;">
                            <?php echo $_SESSION["usuario"]; ?>
                        </th>
                    </tr>
                </table>
            </div>


            <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt; margin-top: 5px;">
                <tr>
                    <th style="width: 20%;text-align:center; font-weight: 800; color: black;">SECCIÓN:</th>
                    <th style="width: 80%;text-align:left;border-bottom: white ;font-style: italic; color: black"><?php echo $seccion; ?></th>
                </tr>
                <tr>
                    <th style="width: 20%;text-align:center;font-weight: 800;color: black;">SECTOR:</th>
                    <th style="width: 80%;text-align:left; border-bottom: white ;font-style: italic; color: black"><?php echo $sector; ?></th>
                </tr>
                <tr>
                    <th style="width: 20%;text-align:center;font-weight: 800;color: black;">EQUIPO:</th>
                    <th style="width: 80%;text-align:left;border-bottom: white ; font-style: italic; color: black"><?php echo $equipo; ?></th>
                </tr>

            </table>
        </div>
        <div style="margin-top: -200px;">
            <div style="display:flex ;justify-content: center;">
                <h3 style="text-align: center; font-size: 1.3rem; font-weight: bold;color:black">EQUIPO</h3>
            </div>
            <div style="display: flex; flex-direction: row; justify-content:space-evenly; ">
                <div style="justify-content: center;">
                    <h3 class="logo-name" style="font-size: 1.1rem;font-weight: bold; color: black">IDPROD</h3>
                    <h3 style="text-align: center;font-size: 10pt; color: black"><?php echo $id_prod ?></h3>

                </div>

                <div style="justify-content: center;">
                    <h3 style="font-size: 1.1rem;text-align: center;font-weight: bold; color: black">DESCRIPCIÓN</h3>
                    <h3 style="text-align: center;font-size: 10pt; color: black"><?php echo $descripcion ?></h3>
                </div>
                <div style="justify-content: center;">
                    <h3 style="font-size: 1.1rem; text-align: center;font-weight: bold; color: black">CANTIDAD</h3>
                    <h3 style="text-align: center;font-size: 10pt; color: black"><?php echo $cantidad ?></h3>
                </div>

            </div>

        </div>
        <table cellspacing="0" style="width: 100%; text-align: left; font-size: 10pt; border: 2px solid black;margin-top: 5px;">
            <tr style="border-bottom: 2px solid black;">
                <th style=" font-size: 0.9rem;text-align:center;border-bottom: 1px solid black; border-right:1px black solid;color: black;">ID PLANO</th>

                <th style="width:110%;text-align:left;vertical-align: bottom;font-size: 0.9rem;border-bottom: 1px solid black;color:black;vertical-align: middle;">DESCRIPCION</th>

            </tr>
            <?php foreach ($_SESSION["archivos_datos"] as $usu) {  ?>
                <tr style="height: 27px;">
                    <td style="width: 10%; text-align: center;font-size:0.9rem; border-right:1px solid black;color: black;"><?php echo $usu["cod_archivo"]; ?></td>
                    <td style="width: 80%; text-align: left;font-size:0.9rem;color: black;position: relative; left: 5px;"><?php echo $usu["archivo"] ?></td>

                </tr>
            <?php } ?>
        </table>

        <br />
        <legend style="text-align: center; font-size: 12px;">USO EXCLUSIVO POR GIULIANI HERMANOS SA - PROHIBIDA SU REPRODUCCION SIN AUTORIZACION</legend>
    </page>

    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>