<?php

//start sesion
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/start-session?=",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
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
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

$token=json_decode($response)->data;
echo "<br><br><br><br>";
//start order
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/order/start-order?=",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"session\": \"$token\",\n\"id_store\":4131,\n\"buyer_user_reference\":\"PRUEBAS\",\n\"app_order_reference\":\"666666\",\n\"buyer_fiscal_number\":\"PRUEBAS\",\n\"buyer_name\":\"Alejandro Hirsch Pruebas\",\n\"buyer_email\":\"ahirsch@paymentez.com\",\n\"buyer_phone\":\"3015319803\",\n\"address_line1\":\"\",\n\"address_line2\":\"\",\n\"address_city\":\"\",\n\"address_state\":\"\",\n\"address_zip\":\"\",\n\"address_country\":\"\",\n\"address_latitude\":\"\",\n\"address_longitude\":\"\",\n\"delivery_instructions\":\"\",\n\"type_order\":0\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: 56bad562-cd91-4252-a68f-552fbec300d7",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
$order_id =json_decode($response)->data->id;
echo "<br><br><br><br>";
// add item

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/order/add-item",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"annotations\": \"ññññá\",\n  \"id_order\": \"$order_id\",\n  \"id_product\": 61682,\n  \"quantity\": 1,\n  \"session\": \"$token\"\n}\n",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: 0f31a88a-be55-43a8-98b1-2ef318343ffc",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);
echo "<br><br><br><br>";
if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
echo "<br><br><br><br>";
// pay order
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/payment/pay-order",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"amount\": 3900,\n  \"currency\": \"string\",\n  \"id_order\": $order_id,\n  \"payment_method_reference\": \"string\",\n  \"payment_reference\": \"string\",\n  \"service\": 0,\n  \"session\": \"$token\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: 6e593ca6-acec-4c31-825e-389752493ddd",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}
echo "<br><br><br><br>";

//place order



$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://middleware.paymentez.com/order/place-order",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"id_order\": \"$order_id\",\n  \"session\": \"$token\"\n}",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/json",
    "Postman-Token: f755aecb-b8fe-45e5-9507-e7f4949dc04d",
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

?>