<?php
$phone = formatPhone($_POST["phone"]);
$validatePhone = validatePhone($phone);

if($validatePhone['status']){

 $getUser = findOne("uni_clients","clients_phone = ?", array($phone));

 if($getUser){
   exit(json_encode(["status" => false, "answer" => $ULang->t("Указанный номер телефона уже используется на сайте!")]));
 }

 $_SESSION["verify_sms"][$phone]["code"] = smsVerificationCode($phone);

 echo json_encode(["status"=>true]);

}else{
 echo json_encode(["status"=>false, "answer"=>$validatePhone['error']]);
}
?>