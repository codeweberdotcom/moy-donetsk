<?php

$merchant = $param["id_shop"];

$time = time();
$price = number_format($paramForm["amount"], 2, ".", "");

$string = "{$merchant};{$config["urlPath"]};{$paramForm["id_order"]};{$time};{$price};{$param["curr"]};{$paramForm["title"]};1;{$price}";

$hash = hash_hmac("md5",$string,$param["private_key"]);

$params["apiVersion"] = 1;
$params["language"] = "ru";
$params["transactionType"] = "CREATE_INVOICE";
$params["merchantAccount"] = $merchant;
$params["merchantAuthType"] = "SimpleSignature";
$params["merchantTransactionSecureType"] = "AUTO";
$params["merchantDomainName"] = $config["urlPath"];
$params["merchantSignature"] = $hash;
$params["orderReference"] = (String)$paramForm["id_order"];
$params["orderDate"] = $time;
$params["amount"] = $price;
$params["currency"] = $param["curr"];
$params["productName"][] = $paramForm["title"];
$params["productPrice"][] = $price;
$params["productCount"][] = 1;

$result = sendPostRequest("https://api.wayforpay.com/api", json_encode($params));

return ["link"=>$result["invoiceUrl"]];

?>