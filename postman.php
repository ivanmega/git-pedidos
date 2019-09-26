 
<?php
$id_order = 0;
if(isset( $_POST['store_id']) && !empty($_POST['store_id']) && isset( $_POST['store_id'])) {

  
  $token=json_decode(sesion())->data;
  $productos = json_decode($_POST['productos']);

  $total_pago = $_POST['total_pago'];
  $order = json_decode(startOrder($_POST['store_id'],$token));
  $status = $order->status;
  if(strcmp ($status, "00" ) != 0){
    echo '<div class="panel panel-danger">
      <div class="panel-heading">Error al iniciar orden error: </div>
      <div class="panel-body">'."Status = ".$status.'</div>
    </div>';
    return;
  }
  $id_order = $order->data->id;

  echo "<br><br><h4><b>order_id = ".$id_order."</b></h4><br>";
   for ($i=0; $i < count($productos); $i++) {
      $status = json_decode(add_item_configuration($token,$id_order,$productos[$i]))->status;
      if(strcmp ($status, "00" ) != 0){
      echo '<div class="panel panel-danger">
      <div class="panel-heading">Error al agregar productos:</div>
      <div class="panel-body">'."Status = ".$status.'</div>
    </div>';
      return;
      }
    }
  $status = json_decode(pay_order($token,$id_order,$total_pago))->status;
  if(strcmp ($status, "00" ) != 0){
      echo '<div class="panel panel-danger">
      <div class="panel-heading">Error al pagar orden:</div>
      <div class="panel-body">'."Status = ".$status.'</div>
    </div>';
      return;
    }
  $status = json_decode(place_order($token,$id_order))->status;
  if(strcmp ($status, "00" ) != 0){
    echo '<div class="panel panel-danger">
    <div class="panel-heading">Error al enviar pedido:</div>
    <div class="panel-body">'."Status = ".$status.'</div>
    </div>';
      return;
    }else{
      echo '<div class="panel panel-success">
      <div class="panel-heading">Pedido generado correctamente:</div>
      <div class="panel-body"><a class="btn btn-primary" href="https://middleware.paymentez.com/admin#/app-order/'.$id_order.'" role="button" target="_blank">App Order</a></div>
    </div>';
    }

} 
function sesion(){
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/start-session?=",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 120,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"app_code\": \"PAYMENTEZPOS\",\n  \"app_key\": \"M4RdFlMmDJ5CTn9sgV2B1jcwNUCDhFmS\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: 102be378-7717-476c-bbe8-6dd84d3a7164",
    "cache-control: no-cache"
),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}
return $response;
}


function getmenu($store_id){
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/store/get-menu?session=".json_decode(sesion())->data."&id_store=".$store_id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 120,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: 6f9c1798-18ec-4d48-a074-2df7744c44ca",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
   return "cURL Error #:" . $err;
} else {
  return $response;
}
return $response;
}


function getstore($store_id){
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/store/list?session=".json_decode(sesion())->data."&storeId=".$store_id,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 120,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => array(
    "Postman-Token: b2f0dc01-3b48-4256-bf0b-4e9d28d8c557",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}
return $response;
}

function startOrder($store_id,$token){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/order/start-order?=",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 120,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"session\": \"$token\",\n\"id_store\":".$store_id.",\n\"buyer_user_reference\":\"PRUEBAS\",\n\"app_order_reference\":\"666666\",\n\"buyer_fiscal_number\":\"PRUEBAS\",\n\"buyer_name\":\"Prueba Integracion Paymentez\",\n\"buyer_email\":\"test-co@paymentez.com\",\n\"buyer_phone\":\"3015319803\",\n\"address_line1\":\"\",\n\"address_line2\":\"\",\n\"address_city\":\"\",\n\"address_state\":\"\",\n\"address_zip\":\"\",\n\"address_country\":\"\",\n\"address_latitude\":\"\",\n\"address_longitude\":\"\",\n\"delivery_instructions\":\"\",\n\"type_order\":0\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: 17dbe9a1-883a-4bd0-abbe-005a631a13a7",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  return "cURL Error #:" . $err;
} else {
  return $response;
}
}

function add_item_configuration($token,$id_order,$objProduct){
$curl = curl_init();
$objProduct->session = $token;
$objProduct->id_order = $id_order;
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/order/add-item-w-configuration",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 120,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($objProduct),
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: 41755631-04b4-4446-b9b5-1c98f4fdfae3",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  return $response;
}
}

function pay_order($token,$id_order,$total_amount){
  $curl = curl_init();
  
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/payment/pay-order",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 120,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"amount\": ".$total_amount.",\n  \"currency\": \"string\",\n  \"id_order\": ".$id_order.",\n  \"payment_method_reference\": \"string\",\n  \"payment_reference\": \"string\",\n  \"service\": 0,\n  \"session\": \"$token\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: e0b4b742-4597-49bb-a98d-2244fbbf0ed1",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  return $response;
}
}

function place_order($token,$id_order){
  $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/order/place-order",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 1,
  CURLOPT_TIMEOUT => 120,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"id_order\": ".$id_order.",\n  \"session\": \"$token\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: 7ea5ee76-755c-4b44-8c6c-7fea183eedd2",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  return $response;
}
}

?>