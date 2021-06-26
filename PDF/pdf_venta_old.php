<?php

session_start();

// Include the main TCPDF library (search for installation path).
require_once('../inc/php/TCPDF/tcpdf.php');

if (isset($_GET["codigo"])){
    $cod_pedido             = intval($_GET["codigo"]);
    include $_SERVER['DOCUMENT_ROOT'] . "/Giuliani/controller/pagos.controller.php";
    $controlador            = PagosController::singleton_pagos();
    $saldado                = 0;
    $articulos_subarticulos = $controlador->getArticulosSubarticulos();
    $estados                = $controlador->getEstados();
    $formas_pagos           = $controlador->getFormasPagos();
    $formas_entregas        = $controlador->getFormasEntregas();
    $tiempos                = $controlador->getTiempos();
    $usuarios               = $controlador->getUsuarios();         
    $usuarios_f               = $controlador->getUsuariosFinales();         
    $articulos              = $controlador->getArticulos();         
    $subarticulos           = $controlador->getSubarticulos();         
    $pedido                 = $controlador->getPedidos($cod_pedido);
    $detalles               = $controlador->getDetalles($cod_pedido);
    $total                  = 0;
    foreach ($detalles as $pos => $dev){
        $total += $dev["cantidad"] * $dev["precio"];
        foreach ($articulos as $aux){
            if ($dev["articulo_id"] == $aux["id"]){
                $detalles[$pos]["descripcion"] = $aux["descripcion"];
                break;
            }
        }
        foreach ($subarticulos as $aux){
            if ($dev["subarticulo_id"] == $aux["id"]){
                $detalles[$pos]["descripcion"] .= " <i>( + " . $aux["descripcion"] . ")</i>";
                break;
            }
        }
    }
    foreach ($formas_entregas as $aux){
        if ($aux["id"] == $pedido["forma_entrega_id"]){
            $pedido["entrega"] = $aux["descripcion"];
        }
    }
    foreach ($estados as $aux){
        if ($aux["id"] == $pedido["pedido_estado_id"]){
            $pedido["estado"] = $aux["descripcion"];
        }
    }
    foreach ($tiempos as $aux){
        if ($aux["id"] == $pedido["tiempo_id"]){
            $pedido["tiempo"] = $aux["descripcion"];
        }
    }
    foreach ($detalles as $pos => $aux){
        $detalles[$pos]["producto"] = "";
        if ($aux["articulo_id"] > 0){
            foreach ($articulos as $art){
                if ($art["id"] == $aux["articulo_id"]){
                    $detalles[$pos]["producto"] .= $art["descripcion"];
                }
            }
        }
        if ($aux["subarticulo_id"] > 0){
            foreach ($subarticulos as $art){
                if ($art["id"] == $aux["subarticulo_id"]){
                    $detalles[$pos]["producto"] .= " (" . $art["descripcion"] . ")";
                }
            }
        }
    }
    $pagos                  = $controlador->getPagosAll($cod_pedido);   
    foreach ($pagos as $pos => $dev){
        $saldado += $dev["importe"];
    }
    $saldo = $total - $saldado;
        
    foreach ($usuarios as $aux){
        if ($pedido["usuario_id"] == $aux["id"]){
            $_usuario = $aux;
        }
    }
    foreach ($usuarios_f as $aux){
        if ($pedido["cliente_id"] == $aux["identificador"]){
            $_usuario_f = $aux;
        }
    }
}

// create new PDF document
$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Labs357');
$pdf->SetTitle('Ticket Venta');
$pdf->SetSubject('Ticket Venta');
$pdf->SetKeywords('PDF, ticket, venta');

$title_header = "Venta";
$subtitle_header = "Fecha: " . date("d/m/Y");
$pdf->SetCellPadding(0);
// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title_header, $subtitle_header, array(0,0,0), array(0,0,0));
$pdf->setFooterData(array(0,0,0), array(0,0,0));

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 5, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


$pdf->setCellHeightRatio(1.25);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 6, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage('L','A5');

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content to print
$html = '
    <table>
        <tr>';
for ($i = 0; $i < 2; $i++){
    $total = 0;
    $html .= '
    <td>
        <table> 
            <thead>
                <tr>
                    <td width="1" align="left">
                        <img src="../imagenes/header_ticket.png" />
                    </td>
                    <td width="150" align="left">
                        <br />
                        Fecha: ' . date("d/m/Y", strtotime($pedido["fecha"])) . '<br />
                        Pedido: #' . $pedido["id"] . '<br />
                        Usuario: <b>' . $_SESSION["usuario"] . '</b><br />
                        Cliente: <b>' . $_usuario_f["apellido"] . ", " . $_usuario_f["nombre"] . '</b><br />
                        Dirección: ' . $pedido["direccion"] . '<br />
                    </td>
                    <td width="150" align="left">
                        <br />
                        Estado: ' . $pedido["estado"] . '<br />
                        Tiempo: ' . $pedido["tiempo"] . '<br />
                        Entrega: ' . $pedido["entrega"] . '<br />
                        Telefono: ' . $pedido["telefono"] . '<br />
                    </td>
                </tr>
            </thead>
        </table> ' . 
        '<table> 
            <thead>
                <tr bgcolor="#EEEEEE">
                    <th width="105" align="left">Código</th>
                    <th width="65" align="right">Cant.</th>
                    <th width="65" align="right">$ Unit</th>
                    <th width="65" align="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>';
            $html .= '<tr>';
            $html .= '<td></td>';
            $html .= '</tr>';
        foreach ($detalles as $reg) { 
            $total += $reg["precio"] * $reg["cantidad"];
            $html .= '<tr>';
            $html .= '<td width="105" align="left" valign="middle"><b>' . $reg["producto"] . '</b></td>';
            $html .= '<td width="65" align="right" valign="middle"><b>' . number_format($reg["cantidad"],2) . '</b></td>';
            $html .= '<td width="65" align="right" valign="middle"><b>' . number_format($reg["precio"],2) . '</b></td>';
            $html .= '<td width="65" align="right" valign="middle"><b>$ ' . number_format($reg["precio"] * $reg["cantidad"],2) . '</b></td>';
            $html .= '</tr>';
        }
        
        $html .= '<tr>';
        $html .= '<td width="105" align="left" valign="middle"><b></b></td>';
        $html .= '<td width="65" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="65" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="65" align="right" valign="middle"><b></b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="105" align="left" valign="middle"><b></b></td>';
        $html .= '<td width="65" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><u>Subtotal</u></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><i>$ ' . number_format($total,2) . '</i></b></td>';
        $html .= '</tr>';
                
        $html .= '<tr>';
        $html .= '<td width="105" align="left" valign="middle"><b></b></td>';
        $html .= '<td width="65" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><u>Descuento</u></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><i>$ ' . number_format($pedido["descuento"],2) . '</i></b></td>';
        $html .= '</tr>';
                
        $html .= '<tr>';
        $html .= '<td width="105" align="left" valign="middle"><b></b></td>';
        $html .= '<td width="65" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><u>Envío</u></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><i>$ ' . number_format($pedido["costo_envio"],2) . '</i></b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="150" align="left" valign="middle"><b></b></td>';
        $html .= '<td width="75" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="75" align="right" valign="middle"><b></b></td>';
        $html .= '</tr>';
        
        /*$html .= '
            <thead>
                <tr bgcolor="#EEEEEE">
                    <th width="150" align="left">Forma de Pago</th>
                    <th width="75" align="right">Fecha</th>
                    <th width="75" align="right">Importe</th>
                </tr>
            </thead>
        ';            
        foreach ($pagos as $reg) { 
            $html .= '<tr>';
            $html .= '<td width="150" align="left" valign="middle"><b>' . $reg["descripcion"] . '</b></td>';
            $html .= '<td width="75" align="right" valign="middle"><b>' . date("d/m/Y", strtotime($reg["fecha"])) . '</b></td>';
            $html .= '<td width="75" align="right" valign="middle"><b>' . number_format($reg["importe"],2) . '</b></td>';
            $html .= '</tr>';
        }*/
        
        
        $html .= '<tr>';
        $html .= '<td width="105" align="left" valign="middle"><b></b></td>';
        $html .= '<td width="65" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><u>TOTAL</u></b></td>';
        $html .= '<td width="65" style="font-size: 10px;"  align="right" valign="middle"><b><i>$ ' . number_format($total - $pedido["descuento"] + $pedido["costo_envio"],2) . '</i></b></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="150" align="left" valign="middle"><b></b></td>';
        $html .= '<td width="75" align="right" valign="middle"><b></b></td>';
        $html .= '<td width="75" align="right" valign="middle"><b></b></td>';
        $html .= '</tr>';
        
        if ($pedido["comentarios"]) {
            $html .= '<tr>';
            $html .= '<td width="300" align="left" valign="middle"><b>Comentarios: </b> '. $pedido["comentarios"] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>
        </table>   
    </td>
    ';
}
$html .= '
</tr></table>   ';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// ---------------------------------------------------------
ob_clean();
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output('Ticket.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
