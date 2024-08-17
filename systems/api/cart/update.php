<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$ids = $_GET["ids"] ? json_decode($_GET["ids"], true) : [];

if(checkTokenAuth($tokenAuth, $idUser) == false){
	exut(json_encode(["data"=>$ids?:null]));
}

if($idUser){
	$getCart = getAll("select * from uni_cart where cart_user_id=?", [$idUser]);	
	if($getCart){
		foreach ($getCart as $key => $value) {
			$getAd = $Ads->get('ads_id=? and ads_status=?', [$value["cart_ad_id"],1]);
			if($getAd){
				if(isset($ids[$value["cart_ad_id"]])){
					$ids[$value["cart_ad_id"]] = $ids[$value["cart_ad_id"]];
				}else{
					$ids[$value["cart_ad_id"]] = $value["cart_count"];
				}
			}
		}
	}

	if($ids){
		foreach ($ids as $id => $count) {
			$get = findOne('uni_cart', 'cart_ad_id=? and cart_user_id=?', [$id, $idUser]);
			if(!$get){
				insert("INSERT INTO uni_cart(cart_ad_id,cart_user_id,cart_date_add,cart_count)VALUES(?,?,?,?)", [$id,$idUser,date("Y-m-d H:i:s"),$count]);
			}else{
				update("UPDATE uni_cart SET cart_count=? WHERE cart_id=?", [$count,$get['cart_id']]);
			}
		}
	}

}

echo json_encode(["data"=>$ids?:null]);

?>