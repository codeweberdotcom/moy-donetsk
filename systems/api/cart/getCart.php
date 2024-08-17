<?php

$idUser = (int)$_GET["id_user"];
$tokenAuth = clear($_GET["token"]);
$ids = $_GET["ids"] ? json_decode($_GET["ids"], true) : [];

$results = [];
$ads = [];
$total_count = 0;
$total_price = 0;

// if($idUser){
// 	$getCart = getAll("select * from uni_cart where cart_user_id=?", [$idUser]);	
// 	if($getCart){
// 		foreach ($getCart as $key => $value) {
// 			$ids[$value["cart_ad_id"]] = $value["cart_count"];
// 		}
// 	}
// }

if($ids){
	foreach ($ids as $id => $count) {
		$getAd = $Ads->get('ads_id=? and ads_status=?', [$id,1]);
		if($getAd){
			if($idUser){
				if($idUser != $getAd["ads_id_user"]){
					$total_count += $count;
					$total_price += $getAd["ads_price"] * $count;
					$ads[$getAd["ads_id_user"]][] = ["data"=>apiArrayDataAd($getAd,$idUser), "count"=>$count, "ad_id"=>$id];
				}
			}else{
				$total_count += $count;
				$total_price += $getAd["ads_price"] * $count;
				$ads[$getAd["ads_id_user"]][] = ["data"=>apiArrayDataAd($getAd,$idUser), "count"=>$count, "ad_id"=>$id];
			}
		}
	}
	if($ads){
		foreach ($ads as $user_id => $data) {
			$getUser = findOne('uni_clients', 'clients_id=? and clients_status=?', [$user_id,1]);
			if($getUser){
				$results[] = ["user"=>["name"=>$Profile->name($getUser), "avatar"=>$Profile->userAvatar($getUser)], "ad"=>$data];
			}
		}
	}
}

echo json_encode(["data"=>$results, "total_count"=>$total_count . ' ' . ending($total_count, apiLangContent('товар'),apiLangContent('товара'),apiLangContent('товаров')), "total_price"=>apiPrice($total_price)]);

?>