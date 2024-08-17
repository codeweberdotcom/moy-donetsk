<?php

$id = (int)$_POST['order_id'];
$status = (int)$_POST['status'];
$idUser = (int)$_POST["id_user"];
$tokenAuth = clear($_POST["token"]);

if(checkTokenAuth($tokenAuth, $idUser) == false){
	http_response_code(500); exit('Authorization token error');
}

if($status == 5){

  $getOrder = findOne("uni_secure", "secure_id_order=? and secure_id_user_buyer=?", [$id,$idUser]);
  
  if($getOrder){

    $Cart->returnAvailable($getOrder["secure_id_order"]);

    update("update uni_secure set secure_status=? where secure_id_order=?", [ 5 , $id ]);

    $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$id]);
    if(count($getAds)){
       foreach ($getAds as $ad) {
          update("update uni_ads set ads_status=? where ads_id=?", [1, $ad["secure_ads_ad_id"] ], true);
       }
    }

    $payments = findOne("uni_secure_payments", "secure_payments_id_order=? and secure_payments_id_user=? and secure_payments_status=?", [$getOrder["secure_id_order"],$getOrder["secure_id_user_buyer"],2]);

    $user = findOne("uni_clients", "clients_id=?", [$getOrder["secure_id_user_buyer"]]);

    if(!$payments && $user){

      if(!$getOrder["secure_balance_payment"]){
        $Ads->addSecurePayments(["id_order"=>$getOrder["secure_id_order"], "amount"=>$getOrder["secure_price"], "score"=>$user["clients_score"], "id_user"=>$getOrder["secure_id_user_buyer"], "status_pay"=>0, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($getOrder["secure_price"], false)]);
      }else{
        $Ads->addSecurePayments(["id_order"=>$getOrder["secure_id_order"], "amount"=>$getOrder["secure_price"], "score"=>$user["clients_score"], "id_user"=>$getOrder["secure_id_user_buyer"], "status_pay"=>1, "status"=>2, "amount_percent" => $Ads->secureTotalAmountPercent($getOrder["secure_price"], false)]);
        $Profile->actionBalance(array("id_user"=>$getOrder["secure_id_user_buyer"],"summa"=>$getOrder["secure_price"],"title"=>$static_msg["61"].' '.$getOrder["secure_id_order"],"id_order"=>generateOrderId(),"email" => $user["clients_email"],"name" => $user["clients_name"], "note" => ""),"+");
      }

    }

    update("delete from uni_clients_orders where clients_orders_uniq_id=?", [$id]);
    update("delete from uni_secure_ads where secure_ads_order_id=?", [$id]);
    update("delete from uni_secure where secure_id_order=?", [$id]);

  }

}elseif($status == 3){

  $getOrder = findOne("uni_secure", "secure_id_order=? and secure_id_user_buyer=?", [$id,$idUser]);
  
  if($getOrder){

    update("update uni_secure set secure_status=? where secure_id_order=?", [ 3 , $id ]);

    if($settings["main_type_products"] == 'physical'){

      $getAds = getAll('select * from uni_secure_ads where secure_ads_order_id=?', [$id]);

      foreach ($getAds as $value) {

           $findAd = $Ads->get("ads_id=?", [$value['secure_ads_ad_id']]);
         
           if(!$findAd['ads_available_unlimitedly'] && !$findAd['ads_available']){
               update("update uni_ads set ads_status=? where ads_id=?", [5,$value['secure_ads_ad_id']], true);                
           }

      }

    }
    
    $payments = findOne("uni_secure_payments", "secure_payments_id_order=? and secure_payments_id_user=?", [$getOrder["secure_id_order"],$getOrder["secure_id_user_seller"]]);

    $user = findOne("uni_clients", "clients_id=?", [$getOrder["secure_id_user_seller"]]);

    if(!$payments && $user){

      $Ads->addSecurePayments(["id_order"=>$getOrder["secure_id_order"], "amount"=>$getOrder["secure_price"], "score"=>$user["clients_score"], "id_user"=>$getOrder["secure_id_user_seller"], "status_pay"=>0, "status"=>1, "amount_percent" => $Ads->secureTotalAmountPercent($getOrder["secure_price"])]);

    }

  }

}elseif($status == 2){

  $getOrder = findOne("uni_secure", "secure_id_order=? and secure_status=?", [$id,1]);
  
  if($getOrder){

      update("update uni_secure set secure_status=? where secure_id_order=? and secure_id_user_seller=?", [ 2 , $id, $idUser]);

  }

}

echo json_encode(['status'=>true]);
?>