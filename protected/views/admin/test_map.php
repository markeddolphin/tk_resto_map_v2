<H2><?php echo t("Geocoding results")?></H2>

<p>
<?php 
dump($geocode);
?>
</p>

<h2><?php echo t("Map results")?></h2>
<?php if($provider=="google.maps"):?>
<p class="uk-text-muted"><?php echo t("If you cannot see the map or the map says This page can't load Google Maps correctly below it means your api is not working, enabled the api Google Maps JavaScript API")?></p>
<?php else :?>
<p class="uk-text-muted"><?php echo t("If you cannot see the map below it means your api key is not working")?>.</p>
<?php endif;?>

<div class="test_map" id="test_map">
</div>

<p>
<a  href="<?php echo Yii::app()->createUrl("admin/settings")?>"><?php echo t("Go back")?></a>
</p>