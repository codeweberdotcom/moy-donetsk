<?php

$id_hash = clear($_POST["id"]);
$support = (int)$_POST["support"];
echo json_encode( array( "count_msg" => $Profile->getMessage($_SESSION["profile"]["id"])["total"], "dialog"=> $Profile->chatDialog($id_hash,$support) ));

?>