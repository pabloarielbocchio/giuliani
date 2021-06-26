<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_STRICT);

session_start();

date_default_timezone_set('America/Argentina/Buenos_Aires');

ini_set("allow_url_fopen", 1);
$fichero    = 'test_webhook.txt';
$inputJSON  = file_get_contents('php://input');
$input      = json_decode($inputJSON, TRUE); //convert JSON into array
$tipo       = $input["type"];
if (trim($tipo) != "payment"){
    die();
}
$id         = $input["data"]["id"];
$token      = "TEST-6131527152518858-060111-d94144e71c87ac78e610ae9d9c158f6c-577528056";
$json       = file_get_contents('https://api.mercadopago.com/v1/payments/'.$id.'?access_token='.$token);
$obj        = json_decode($json);
$estado     = $obj->status;
$modo       = intval($obj->live_mode); // false = testeo
$ext_ref    = $obj->external_reference;
$monto      = $obj->transaction_amount;
$_id        = $obj->id;
// "external_reference": "idPedido:1|cuenta:100300|cliente:1207145112731263"
$parametro  = explode("|", $ext_ref);
$idPedido   = explode(":", $parametro[0])[1];
$cuenta     = explode(":", $parametro[1])[1];
$cliente    = explode(":", $parametro[2])[1];

$_SESSION["db"] = "_" . $cuenta;

include_once $_SERVER['DOCUMENT_ROOT']."/Giuliani/bd/conexion.php";
class PagosModel {
    private $conn;
    public function __construct() {
        try {                
            $this->conn = Conexion::singleton_conexion();
        } catch ( Exception $e ) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }  
    function sendToMessenger($token, $data) {
        $data = json_encode($data);
        $url = "https://graph.facebook.com/v3.2/me/messages?access_token=$token";
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => $data
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    public function getFormasPagos(){
        try {
            $sql = "SELECT 
                        *,
                        DATE_FORMAT(fecha_m, '%Y-%m-%d %H:%i:%s') as fecha_modif
                    FROM 
                        orders_forma_pago;";
            $query = $this->conn->prepare($sql);
            $query->execute();
            if ($query->rowCount() > 0) {
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
        } catch (PDOException $e) {
            $error = "Error!: " . $e->getMessage();
            return $error;
        }
    }
    public function addPagos($fecha, $pago, $importe, $cod_pedido, $payment_id, $live_mode){
        $hoy = date("Y-m-d");
        $ahora = date("Y-m-d H:i:s");
        try {
            $user_id = -99;
            $this->conn->beginTransaction();            
            $stmt = $this->conn->prepare('INSERT INTO orders_pago (fecha, pedido_id, forma_pago_id, importe, payment_id, live_mode, usuario_m, fecha_m) VALUES (?,?,?,?,?,?,?,?);');
            $stmt->bindValue(1, $fecha, PDO::PARAM_STR);
            $stmt->bindValue(2, $cod_pedido, PDO::PARAM_INT);
            $stmt->bindValue(3, $pago, PDO::PARAM_INT);
            $stmt->bindValue(4, $importe, PDO::PARAM_STR);
            $stmt->bindValue(5, $payment_id, PDO::PARAM_INT);
            $stmt->bindValue(6, $live_mode, PDO::PARAM_INT);
            $stmt->bindValue(7, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(8, $ahora, PDO::PARAM_STR);            
            if($stmt->execute()){
                $this->conn->commit();
                $_SESSION["JSON"]["status"] = "Success";
                $_SESSION["JSON"]["data"] = 0;
                return 0;
            }  else {
                $this->conn->rollBack();
                $_SESSION["JSON"]["status"] = "Error";
                $_SESSION["JSON"]["message"] = $stmt->errorInfo();
                return var_dump($stmt->errorInfo());
            }
        } catch(PDOException $e) {
            $this->conn->rollBack();
            $_SESSION["JSON"]["status"] = "Error";
            $_SESSION["JSON"]["message"] = $error;
            return -1;
        }
    }
}
$devuelve = "";
$pago       = new PagosModel();
if (trim($estado) == "approved"){
    //http://labs357.com.ar/messengerhilo.php?numcta=100300&palabra=_pago%20aprobado&sender=1728721160477981    
    $envio_rta = file_get_contents('http://labs357.com.ar/messengerhilo.php?numcta=' . $cuenta . ' &palabra=_pago%20aprobado&sender=' . $cliente);
    $pago->sendToMessenger($token, $envio_rta);
    $fp         = $pago->getFormasPagos();
    $forma_pago = 0;
    foreach ($fp as $aux) { 
    if ($aux["mercadopago"] == 1){// and $aux["mercadopagoToken"] == $token){ 
            $forma_pago = $aux["id"];
            break;
        }
    }
    $fecha      = date("Y-m-d H:i:s");
    $importe    = floatval($monto);
    $cod_pedido = intval($idPedido);
    $payment_id = intval($_id);
    $live_mode  = intval($modo);
    $devuelve   = $pago->addPagos($fecha, $forma_pago, $importe, $cod_pedido, $payment_id, $live_mode);
} else {
    //http://labs357.com.ar/messengerhilo.php?numcta=100300&palabra=_pago%20rechazado&sender=1728721160477981
    $envio_rta = file_get_contents('http://labs357.com.ar/messengerhilo.php?numcta=' . $cuenta . ' &palabra=_pago%20rechazado&sender=' . $cliente);
    $pago->sendToMessenger($token, $envio_rta);
}

$actual     = "";
$actual    .= "type: " . $input["type"] . " \n";
$actual    .= "id: " . $input["data"]["id"] . " \n";
$actual    .= "status: " . $estado . " \n";
$actual    .= "live_mode: " . strval($modo) . " \n";
$actual    .= "external_reference: " . $ext_ref . " \n";
$actual    .= "transaction_amount: " . $monto . " \n";
$actual    .= "_id: " . $_id . " \n";
$actual    .= "idPedido: " . $idPedido . " \n";
$actual    .= "cuenta: " . $cuenta . " \n";
$actual    .= "cliente: " . $cliente . " \n";
$actual    .= "devuelve: " . $devuelve . " \n";
file_put_contents($fichero, $actual);
?>