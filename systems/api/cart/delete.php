<?php
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$id = (int)$_POST["id"];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update("DELETE FROM uni_cart WHERE cart_ad_id=? and cart_user_id=?", [$id,$idUser]);

echo json_encode(['status'=>true]);
?>