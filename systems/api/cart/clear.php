<?php
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

update("DELETE FROM uni_cart WHERE cart_user_id=?", [$idUser]);

echo json_encode(['status'=>true]);
?>