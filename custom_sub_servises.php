<?php
/*echo '<pre>';
var_dump($data['geo']['city_alias']);
echo '</pre>';
*/

$result = null;

// Проверяем, существует ли элемент и не равен ли он NULL
if (isset($data['geo']['city_alias']) && $data['geo']['city_alias'] !== null) {
    $result = '/' . $data['geo']['city_alias'];
} else {
    $result = '/russia';
}


if ($data['category']['category_board_id'] == 276) { //Строительство и ремонт (Товары)
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/uslugi/stroitelstvo">Строительные услуги</a></div>
<?php

} elseif ($data['category']['category_board_id'] == 165) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/stroymaterialy-i-instrumenty">Строительные товары</a></div>
<?php
} elseif ($data['category']['category_board_id'] == 729) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/noutbuki-i-aksessuary/zapchasti-dlya-noutbukov-i-planshetov">Запчасти для ноутбуков и планшетов</a></div>
<?php
} elseif ($data['category']['category_board_id'] == 302) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/kompyutery-i-periferiya/periferiya/karty-pamyati">Карты памяти</a></div>
<?php
} elseif ($data['category']['category_board_id'] == 697) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/kompyutery-i-periferiya/periferiya/igrovye-naushniki">Игровые наушники</a></div>
<?php
} elseif ($data['category']['category_board_id'] == 1072) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/noutbuki-i-aksessuary/noutbuki">Ноутбуки</a></div>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/kompyutery-i-periferiya/kompyutery">Системные блоки</a></div>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/kompyutery-i-periferiya/monitory">Мониторы</a></div>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/tv-audio-video/proektory">Проекторы</a></div>
<?php
} elseif ($data['category']['category_board_id'] == 59) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/ofisnaya-tehnika">Офисная техника</a></div>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/ofisnaya-tehnika/oborudovanie-dlya-torgovli">Электроника для торговли</a></div>


<?php
} elseif ($data['category']['category_board_id'] == 1170) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/elektronika/bytovaya-tehnika/krasota-i-zdorove/massazhnoe-oborudovanie/massazhnye-matrasy-i-kovriki">Матрасы и коврики</a></div>



<?php
} elseif ($data['category']['category_board_id'] == 1223) { //Услуги->Строительство
?>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/stroymaterialy-i-instrumenty/stroymaterialy/metalloprokat/listovoy-prokat">Листовой прокат</a></div>
    <div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="<?php echo $result; ?>/stroymaterialy-i-instrumenty/krepezh-i-furnitura/furnitura-i-komplektuyushchie-dlya-mebeli/drevesnoplitnye-materialy">Древесноплитные материалы</a></div>



<?php
} else {
    return null;
}
