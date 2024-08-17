<?php
$id_ad = intval($_POST["id_ad"]);
$Ads->smartUserDelete($id_ad, intval($_SESSION["profile"]["id"]));
?>