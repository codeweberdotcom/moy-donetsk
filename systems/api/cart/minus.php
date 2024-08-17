<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$id = (int)$_GET["id"];
$count = (int)$_GET["count"] ?: 1; 

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($idUser){

	$get = findOne('uni_cart', 'cart_ad_id=? and cart_user_id=?', [$id, $idUser]);
	if($get){

		if($get["cart_count"] > 1){
			update("UPDATE uni_cart SET cart_count=? WHERE cart_id=?", [$count,$get['cart_id']]);
		}else{
			update("DELETE FROM uni_cart WHERE cart_id=?", [$get['cart_id']]);
		}

	}

}

echo json_encode(["status"=>true]);

?>