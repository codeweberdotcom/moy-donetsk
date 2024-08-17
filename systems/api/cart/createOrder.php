<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$ids = $_POST["ids"] ? json_decode($_POST["ids"], true) : '';

$results = [];
$ads = [];
$total_count = 0;
$total_price = 0;

$cart = [];

if($ids){
	foreach ($ids as $id => $count) {
		$getAd = $Ads->get('ads_id=?', [$id]);
		if($getAd){
			$total_count += $count;
			$total_price += $getAd["ads_price"] * $count;
		    $cart[$id]['count'] = $count;
		    $cart[$id]['ad'] = $getAd;
		}
	}
}

if(!$settings["secure_payment_service_name"]){
   exit(json_encode(array("status" => false, "answer" => apiLangContent("Платежная система не определена!"))));
}

$orderId = generateOrderId();

$answer = $Profile->payMethod( $settings["secure_payment_service_name"] , array( "amount" => $total_price, "id_order" => $orderId, "id_user" => $idUser, "action" => "marketplace", "title" => $static_msg["11"]." №".$orderId, 'cart' => $cart) );

echo json_encode(["status" => true, "link" => $answer['link'], "order_id" => $orderId]);

?>