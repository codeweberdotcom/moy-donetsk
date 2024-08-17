<?php
/*echo '<pre>';
var_dump($data['category']);
echo '</pre>';
*/

if ($data['category']['category_board_id'] == 276) { //Строительство и ремонт (Товары)
       ?>
<div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="/uslugi/stroitelstvo">Строительные услуги</a></div>
<?php
	
}elseif($data['category']['category_board_id'] == 165){ //Услуги->Строительство
?>
<div class="col-lg-3 col-12 col-md-4 col-sm-4"><a {active}="" href="/stroymaterialy-i-instrumenty">Строительные товары</a></div>
<?php
    } else {
        return null;
}


