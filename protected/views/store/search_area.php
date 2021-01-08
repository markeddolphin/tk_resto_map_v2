<?php 
if (!isset($_SESSION)) { session_start(); }

$_SESSION['search_type']='';
if (isset($_GET['s'])){
	$_SESSION['kr_search_address']=$_GET['s'];
	$_SESSION['search_type']='kr_search_address';
}

if (isset($_GET['foodname'])){
	$_SESSION['kr_search_foodname']=$_GET['foodname'];
	$_SESSION['search_type']='kr_search_foodname';
}

if (isset($_GET['category'])){
	$_SESSION['kr_search_category']=$_GET['category'];
	$_SESSION['search_type']='kr_search_category';
}

if (isset($_GET['restaurant-name'])){
	$_SESSION['kr_search_restaurantname']=$_GET['restaurant-name'];
	$_SESSION['search_type']='kr_search_restaurantname';
}

if (isset($_GET['street-name'])){
	$_SESSION['kr_search_streetname']=$_GET['street-name'];
	$_SESSION['search_type']='kr_search_streetname';
}


unset($_SESSION['kr_item']);
unset($_SESSION['kr_merchant_id']);

$marker=Yii::app()->functions->getOptionAdmin('map_marker');
if (!empty($marker)){
   echo CHtml::hiddenField('map_marker',$marker);
}
?>

<?php if ( Yii::app()->functions->getOptionAdmin('enabled_search_map')=="yes"):?>
<div class="search-map-wrap" id="search-map-wrap">

</div>  <!--search-map-wrap-->
<?php endif;?>

<div class="search-result-wrapper">
  <div class="main" style="overflow:hidden;">    
  <div class="inner">

  <!--<button class="uk-button" data-uk-tooltip="{pos:'bottom-left'}" title="test tip">test</button>-->
  
   <div class="grid">
   
   <a href="javascript:;" class="uk-button uk-button-success filter-search-bar">
   <i class="fa fa-filter"></i> <?php echo t("Filter results")?>
   </a>
   
   <div class="grid-1 left filter-options" > 
     <?php echo CHtml::hiddenField('sort_filter','')?>
     <?php Yii::app()->widgets->searchMerchant()?>
     <?php Yii::app()->widgets->searchFreeDelivery()?>
     <?php Yii::app()->widgets->searchByDeliveryType()?>     
     <?php Yii::app()->widgets->searchByCuisine()?>     
     <?php Yii::app()->widgets->searchMinimumOrder()?>     
                    
   </div> <!--END grid-1-->

   <div class="grid-2 left">
   
   <div data-uk-dropdown="{mode:'click'}" class="uk-button-dropdown">
	    <button class="uk-button"><?php echo Yii::t("default","Sort By")?> <span class="sortby_text"></span><i class="uk-icon-caret-down"></i></button>
	    <div class="uk-dropdown" >
	        <ul class="uk-nav uk-nav-dropdown">
				<li>
				<a href="javascript:;" data-id="restaurant_name" class="sort_filter" data-text="<?php echo t("Name")?>">
				<?php echo Yii::t("default","Name")?>
				</a>				
				</li>
				
				<li>
				<a href="javascript:;" data-id="ratings" class="sort_filter" data-text="<?php echo t("Rating")?>" >
				<?php echo Yii::t("default","Rating")?>
				</a>
				</li>
				
				<li>
				<a href="javascript:;" data-id="minimum_order" class="sort_filter" data-text="<?php echo t("Minimum")?>">
				<?php echo Yii::t("default","Minimum")?>
				</a>
				</li>
				
				<li>
				<a href="javascript:;" data-id="distance" class="sort_filter" data-text="<?php echo t("Distance")?>">
				<?php echo Yii::t("default","Distance")?>
				</a>
				</li>
				
	        </ul>
	    </div>
	</div>
    
	<form id="frm_table_list" method="POST" >
    <input type="hidden" name="action" id="action" value="searchArea">
    <?php echo CHtml::hiddenField('currentController','store')?>
    <input type="hidden" name="tbl" id="tbl" value="searchArea">
    <?php 
       if (isset($_GET['restaurant-name'])){
       	   echo CHtml::hiddenField('restaurant-name',isset($_GET['restaurant-name'])?$_GET['restaurant-name']:'');
       } elseif (isset($_GET['street-name'])) {
       	   echo CHtml::hiddenField('street-name',isset($_GET['street-name'])?$_GET['street-name']:'');
       } elseif (isset($_GET['category'])) {  
       	   echo CHtml::hiddenField('category',isset($_GET['category'])?$_GET['category']:'');
       	} elseif (isset($_GET['foodname'])) {  
       	   echo CHtml::hiddenField('foodname',isset($_GET['foodname'])?$_GET['foodname']:''); 
       } elseif (isset($_GET['stype'])) {  
       	   echo CHtml::hiddenField('stype',isset($_GET['stype'])?$_GET['stype']:'');  
       	   switch ($_GET['stype']) {
       	   	case 1:
       	   		echo CHtml::hiddenField('zipcode',isset($_GET['zipcode'])?$_GET['zipcode']:'');       	   		
       	   		break;
       	   	case 2:
       	   		echo CHtml::hiddenField('city',isset($_GET['city'])?$_GET['city']:''); 
       	   		echo CHtml::hiddenField('area',isset($_GET['area'])?$_GET['area']:''); 
       	   		break;
       	   	case 3:
       	   		echo CHtml::hiddenField('address',isset($_GET['address'])?$_GET['address']:'');       	   		
       	   		break;	
       	   	default:
       	   		break;
       	   }
       } else {
       	   echo CHtml::hiddenField('s',isset($_GET['s'])?$_GET['s']:'');
       }
       
       echo CHtml::hiddenField('st',isset($_GET['st'])?$_GET['st']:'',array('class'=>"st"));
    ?>    
    <table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
    <thead>
    <tr>
      <th width="8%"></th>
      <th width="20%"><?php echo Yii::t("default","Restaurant")?></th>
      <th width="8%"><?php echo Yii::t("default","Rating")?></th>
      <th width="5%"><?php echo Yii::t("default","Minimum")?></th>
      <!--<th width="5%">Pay By</th>-->
    </tr>
    </thead>
    <tbody>       
    </tbody>    
    </table>  
    </form>
    
    <div class="clear"></div>
    <div class="ops_notification">    
    <h3><?php echo Yii::t("default","Oops. We're having trouble finding that address.")?></h3>
    <p><?php echo Yii::t("default","Please enter your address in one of the following formats and try again. Please do NOT enter your apartment or floor number here.")?></p>
    <p class="uk-text-bold">- <?php echo Yii::t("default","Street address, city, state")?></p>
    <p class="uk-text-bold">- <?php echo Yii::t("default","Street address, city")?></p>
    <p class="uk-text-bold">- <?php echo Yii::t("default","Street address, zip code")?></p>
    </div> <!--ops_notification-->

    </div>  <!--END grid-1-->   
    <div class="clear"></div>
    </div> <!--END GRID-->
    
    </div>
  </div> <!--END MAIN-->
</div> <!--END search-result-wrapper-->