<?php

$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);
$score = clear($_POST["score"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
  http_response_code(500); exit('Authorization token error');
}

update("update uni_clients set clients_score_booking=? where clients_id=?", [$score ? encrypt($score) : '',$idUser]);
echo json_encode( ["status"=>true] );

?>