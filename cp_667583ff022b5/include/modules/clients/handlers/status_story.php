<?php

define('unisitecms', true);
session_start();

$config = require "../../../../../config.php";
require_once( $config["basePath"] . "/systems/unisite.php");
require_once( $config["basePath"] . "/" . $config["folder_admin"] . "/lang/" . $settings["lang_admin_default"].".php" );

include "../../../../../telegram.php";

if( !(new Admin())->accessAdmin($_SESSION['cp_control_clients']) ){
   $_SESSION["CheckMessage"]["warning"] = "Ограничение прав доступа!";
   exit;
}



if(isAjax() == true){

    $mediaId = (int)$_POST['media_id'];
    $userId = (int)$_POST['user_id'];

    $getStory = findOne('uni_clients_stories', 'clients_stories_user_id=?', [$userId]);

    if(!$getStory){
      smart_insert('uni_clients_stories', [
        'clients_stories_user_id'=>$userId,
        'clients_stories_loaded'=>1,
        'clients_stories_timestamp'=>date("Y-m-d H:i:s"),
      ]);   
    }else{
      update('update uni_clients_stories set clients_stories_timestamp=? where clients_stories_user_id=?', [date("Y-m-d H:i:s"), $userId]);
    }
	
//Создаем объект Класса Телеграм
//$chat_id = '@moydonetskru';	   
$chat_id = '@test_moy_donetsk';
$bot_token = '7301823510:AAEgS9xSFwtOAwJX1hp1C8wANkAvXI5R9BQ';
$telegramPost = new TelegramPost($bot_token, $chat_id);


$getStory = findOne('uni_clients_stories', 'clients_stories_user_id=?', [$userId]);	


	
/*$query = "clients_stories_media_status=1";
$LINK = "?route=stories&sort=1";
$count = getOne("SELECT count(*) as total FROM uni_clients_stories_media WHERE $query")["total"];
	
$getStories =findOne('uni_clients_stories_media', 'clients_stories_media_user_id=?', [$userId])['clients_stories_media_name'];     


$fdsfsdf = $config['urlPath'] . '/'.$config["media"]["user_stories"] .'/' . $getStories;    

$dataTelegram = [
    'text' => $getStories,
	'photos' => [$fdsfsdf], 
	'button_text' => 'Подробнее',
    'button_url' => 'https://donmap.ru/wp-content/uploads/2024/05/banner_psb-6633e16b2ad9a.jpg',
];
	$messageId = $telegramPost->sendPost($dataTelegram);*/

    update("UPDATE uni_clients_stories_media SET clients_stories_media_status=?,clients_stories_media_timestamp=? WHERE clients_stories_media_id=?", [intval($_POST["status"]),date('Y-m-d H:i:s'),$mediaId]);
	
	

    $_SESSION["CheckMessage"]["success"] = "Действие успешно выполнено!";          
    echo true;

}

?>