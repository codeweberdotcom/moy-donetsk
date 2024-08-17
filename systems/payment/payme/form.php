<?php
$amount = $paramForm["amount"]*100;
if($param["test"]){
    $action = "https://test.paycom.uz";
}else{
    $action = "https://checkout.paycom.uz";
}

$link = $action.'/'.base64_encode("m=".$param["merchant_id"].";ac.id_order=".$paramForm["id_order"].";a=".$amount);

return ["link"=>$link];
?>
