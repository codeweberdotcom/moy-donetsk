<?php 

$value = $Ads->getDataAd($value);

$images = $Ads->getImages($value["ads_images"]);
$service = $Ads->adServices($value["ads_id"]);
$getShop = $Shop->getUserShop($value["ads_id_user"]);
?>
<div class="col-lg-6 col-md-6 col-sm-6 col-6" >
  <div class="item-grid <?php echo $service[2] || $service[3] ? "ads-highlight" : ""; ?>" title="<?php echo $value["ads_title"]; ?>" >
	  
	  <?php if($getShop['clients_shops_title']){ ?>
     
<?php }; ?>
	  
	  
     <div class="item-grid-img" >
		  <?php if($getShop['clients_shops_title']){ ?>
      <a href="<?php echo $Profile->userLink($value); ?>" title="<?php echo $getShop['clients_shops_title']; ?>" class="mini-avatar position-absolute bottom-0 d-flex flex-column-reverse right-0 p-2 z-index-1" > <span class="mini-avatar-img shadow" ><img src="<?php echo $Profile->userAvatar($value); ?>" /></span> </a>
<?php }; ?>
     <a href="<?php echo $Ads->alias($value); ?>" target="_blank" title="<?php echo $value["ads_title"]; ?>" >

       <div class="item-labels" >
          <?php echo $Ads->outStatus($service, $value); ?>
       </div>

       <?php echo $Ads->CatalogOutAdGallery($images, $value); ?>
		 
		 <?php  if (strpos($value["ads_filter_tags"], "Предзаказ") !== false) {
            ?><span class="user-card-verification-status position-absolute predzakaz">Предзаказ</span><?php
         } ?>

     </a>
     <?php echo $Ads->adActionFavorite($value, "catalog", "item-grid-favorite"); ?>
     </div>
     <div class="item-grid-info" >

        <div class="item-grid-price" >
         <?php
               echo $Ads->outPrice( [ "data"=>$value,"class_price"=>"item-grid-price-now","class_price_old"=>"item-grid-price-old", "shop"=>$getShop, "abbreviation_million" => true ] );
         ?>        
        </div>
        <a href="<?php echo $Ads->alias($value); ?>" target="_blank" ><?php echo custom_substr($value["ads_title"], 35, "..."); ?></a>

        <span class="item-grid-city" >
         <?php 
             echo $Ads->outAdAddressArea($value);
         ?>
        </span>
        <span class="item-grid-date" ><?php echo datetime_format($value["ads_datetime_add"], false); ?></span>

     </div>
  </div>
</div>