<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

//include "../../../../../telegram.php";
//include "../../../../../image_merger.php";


if( !(new Admin())->accessAdmin($_SESSION['cp_control_shops']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}

$id = intval($_POST["id"]);
$status = intval($_POST["status"]);
$comment = clear($_POST["comment"]);

$getShop = findOne("uni_clients_shops", "clients_shops_id=?", [$id]);
$getUser = findOne("uni_clients", "clients_id=?", array($getShop["clients_shops_id_user"]));

if(isAjax() == true){

   if($status == 2){

      if(!$comment){
          $_SESSION["CheckMessage"]["error"] = "Укажите причину отклонения!";          
          echo json_encode(["status"=>false]);   
          exit;
      }else{

         update("UPDATE uni_clients_shops SET clients_shops_status=?,clients_shops_status_note=? WHERE clients_shops_id=?", [$status,$comment,$id]);

         $data = array("{SHOP_TITLE}"=>$getShop["clients_shops_title"],
                       "{SHOP_LINK}"=>$Shop->linkShop($getShop["clients_shops_id_hash"]),
                       "{USER_NAME}"=>$getUser["clients_name"],                          
                       "{UNSUBSCRIBE}"=>"",                          
                       "{EMAIL_TO}"=>$getUser["clients_email"]
                       );

         email_notification( array( "variable" => $data, "code" => "SHOP_MODERATION_CANCEL" ) );

      }

   }elseif($status == 1){
	   

$getAd = findOne("uni_ads", "ads_id=?", [$_POST["id"]]); 
	   
//Формирование телефонного номера	   
$userPhone = $getUser['clients_phone'];	   
$formattedPhoneNumber = '+' . substr($userPhone, 0, 1) . '(' . substr($userPhone, 1, 3) . ')' . substr($userPhone, 4, 3) . '-' . substr($userPhone, 7, 2) . '-' . substr($userPhone, 9);
	   	   
$title = $getShop["clients_shops_title"]; 
$desc = $getShop["clients_shops_desc"]; 
$link = $Shop->linkShop($getShop["clients_shops_id_hash"]);
$logo = Exists($config["media"]["other"], $getShop["clients_shops_logo"], $config["media"]["no_image"]); 
$get_shop_slider = findOne('uni_clients_shops_slider','clients_shops_slider_id_shop=? order by clients_shops_slider_id asc', [$_POST["id"]]);
if($get_shop_slider["clients_shops_slider_image"]){ 
$image = $config["urlPath"] . "/" . $config["media"]["user_attach"] . "/" . $get_shop_slider["clients_shops_slider_image"];	   
};	
$shop_id = $_POST["id"];	   

	   
//Получаем категорию магазина
$clients_shops_id_theme_category = $getShop->clients_shops_id_theme_category;	   
$CategoryBoard = new CategoryBoard();
$getCategoryBoard = $CategoryBoard->getCategories("where category_board_visible=1");  
$desired_id = $clients_shops_id_theme_category; 
if (isset($getCategoryBoard['category_board_id'][$desired_id])) {
    $category_board_name = $getCategoryBoard['category_board_id'][$desired_id]['category_board_name'];
    $cat = $category_board_name;
	$cat_hashtag = '#'. str_replace(' ', '_', $category_board_name).'_md';
} else{
    $cat = NULL;
}	   
$count_ads = $Ads->getCount("ads_status='1' and clients_status IN(0,1) and ads_id_user='". $getShop["clients_shops_id_user"]."'");
	   
//Регион магазина
$Geo = new Geo();
$getUser = findOne("uni_clients", "clients_id=?", array($getUser['clients_id']));
$region = $Geo->userGeo( ["city_id"=>$getUser["clients_city_id"]]);
$city = str_replace(' ', '_', strstr($region, ',', true));
$cat_hashtag .= "\n#". $city .'_md'; 
	   

// Example usage 
//$merger = new ImageMerger();
//$logoUrl = $logo;
//$bannerUrl = $image;
//$imageUrl = $merger->mergeImagesAndReturnUrl($logoUrl, $bannerUrl);
	   

//Формирование поста Телеграм
$textMessage = "<b>". $title . "</b>. \n \n";
$textMessage .= $desc . "\n \n";
$textMessage .= "<b>Регион:</b> ". $region . "\n";	
$textMessage .= "<b>Телефон:</b> ". $formattedPhoneNumber . "\n \n";		   
$textMessage .= "<b>Категория: </b>";	   
$textMessage .= $cat . "\n";
$textMessage .= $cat_hashtag;

$mediaItem = [$imageUrl];

if(count($mediaItem) >= 2){
$textMessage .= "\n \n👉✅<a href='" . $link . "'>Смотреть объявления на сайте Мой Донецк</a>";
}
	   
	   



function sendPostAsync($url, $data) {
    $jsonData = json_encode($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false); // Не ждем ответа
    curl_setopt($ch, CURLOPT_TIMEOUT, 1); // Ограничение времени выполнения
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);
    curl_exec($ch);
    curl_close($ch);
}

	   
$url = 'https://tg.moy-donetsk.ru/cp_974390432400534534.php';
$data = [
    'chat_id' => '@test_moy_donetsk',
    'text' => $textMessage,
    'photos' => [$mediaItem],
    'button_text' => $count_ads . ' Объявлений',
    'button_url' => $link,
    'telegram_post_id' => null,
	'logo_url' => $logo,
    'banner_url' => $image,
	'shop_id' => $shop_id
];

// Отправка POST-запроса асинхронно
sendPostAsync($url, $data);



	   
//Отправка поста в Телеграм
//$messageId = $telegramPost->sendPost($dataTelegram);

	   
//Создаем Объект Класса Json   
//$store->set($chat_id, $_POST["id"], $messageId);
   

	   

update("UPDATE uni_clients_shops SET clients_shops_status=?,clients_shops_status_note=? WHERE clients_shops_id=?", [$status,"",$id]);

      $data = array("{SHOP_TITLE}"=>$getShop["clients_shops_title"],
                    "{SHOP_LINK}"=>$Shop->linkShop($getShop["clients_shops_id_hash"]),
                    "{USER_NAME}"=>$getUser["clients_name"],                          
                    "{UNSUBSCRIBE}"=>"",                          
                    "{EMAIL_TO}"=>$getUser["clients_email"]
                    );

      email_notification( array( "variable" => $data, "code" => "SHOP_MODERATION_PUBLISHED" ) );

   }

   $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";   
   echo json_encode(["status"=>true]);

}

?>