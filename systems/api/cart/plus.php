<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$id = (int)$_GET["id"];
$count = (int)$_GET["count"] ?: 1; 

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($idUser){

	$getAd = findOne('uni_ads', 'ads_id=?', [$id]);

    if($getAd){

        if($getAd['ads_available_unlimitedly']){
            update("UPDATE uni_cart SET cart_count=? WHERE cart_ad_id=? and cart_user_id=?", [$count,$id, $idUser]);
        }else{

        	$get = getAll('select * from uni_cart where cart_ad_id=? and cart_user_id=?', [$id, $idUser]);

            if(count($get) > $getAd['ads_available']){
                update("UPDATE uni_cart SET cart_count=? WHERE cart_ad_id=? and cart_user_id=?", [$getAd['ads_available'], $id, $idUser]);
            }else{
            	update("UPDATE uni_cart SET cart_count=? WHERE cart_ad_id=? and cart_user_id=?", [$count,$id, $idUser]);
            }

        }	

    }

}

echo json_encode(["status"=>true]);

?>