<?php

$id = (int)$_POST['id_order'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

$getOrder = findOne("uni_ads_booking","ads_booking_status_pay=? and ads_booking_id_order=?", [1,$id]);

if($getOrder){
   echo json_encode(['status'=>true]);
}else{
   echo json_encode(['status'=>false]);
}

?>