
<?php if (is_array($data) && count($data)>=1):?>
<ul class="state-ul">
<?php $y=1;?>
<?php foreach ($data as $val):?>

<li class="state-li" data-state_id="<?php echo $val['state_id']?>">
<div class="state-list button-border">
   <div class="state">
     <div> 
       <span style="padding-right:8px;">       
       <b><?php echo $y;?>. <?php echo $val['name']?> <?php //echo t("State")?></b>
       </span>
       
       <a href="javascript:;"        
       data-stateid="<?php echo $val['state_id']?>" 
       data-countryid="<?php echo $val['country_id']?>"
       class="edit_state"
       >
         <i class="fa fa-pencil"></i>
       </a>
       
       <a href="javascript:;" class="add_city add_city1" data-id="<?php echo $val['state_id']?>" >
        <i class="fa fa-plus"></i>
       </a>
       
       <a href="javascript:;" class="collapse-state">
        <i class="fa fa-compress" aria-hidden="true"></i>
       </a>
       
     </div>  
     
     <div class="state-list state-list-li">
        <?php if ( $data_city=FunctionsV3::locationCityList($val['state_id'])): $x=1;?>
        <?php foreach ($data_city as $city_val): //dump($city_val)?>
          <a href="javascript:;" class="button-pad edit_city"
          data-id="<?php echo $city_val['city_id']?>"
          data-stateid="<?php echo $val['state_id']?>"
          >
           <?php echo $x?>. <?php echo $city_val['name']?>
          </a>
          <?php $x++;?>
          
          <div class="district-list button-border">
	           <p style="margin:0;">
	              <?php echo t("District/Area")?>
	              <span class="uk-text-muted text-small">(<?php echo t("drag the list to sort")?>)</span>
	           </p>	           	          
	           <?php if ($data_area=FunctionsV3::locationAreaList($city_val['city_id'])):?>
	           
	           <ul class="area-list">
	           <?php foreach ($data_area as $area_val):?>
	           <li data-area_id="<?php echo $area_val['area_id']?>">
		           <a href="javascript:;" class="button-pad edit_area"
	               data-cityid="<?php echo $city_val['city_id']?>"
	               data-areaid="<?php echo $area_val['area_id']?>"
		           >
		              <?php echo $area_val['name']?>
		           </a>
	           </li>	                      	           	           
	           <?php endforeach;?>	          
	           </ul>
	           <?php endif;?>	  
	                    
	           <a href="javascript:;" class="button-pad add_area" 
                 data-cityid="<?php echo $city_val['city_id']?>"                 
               >
	            <i class="fa fa-plus"></i> 
	            <?php echo t("ADD NEW DISTRICT / AREA")?>
	          </a>	          
         </div>
          
        <?php endforeach;?>
        <?php endif;?>
        
         <div class="actions">
          <a href="javascript:;" class="button-pad add_city" data-id="<?php echo $val['state_id']?>">
            <i class="fa fa-plus"></i> 
            <?php echo t("ADD NEW CITY")?>
          </a>
        </div>        
        
     </div><!-- state-list-->   
     
   </div> <!--state-->
</div> <!--state-list-->
</li>
<?php $y++;?>
<?php endforeach;?>
<?php endif;?>
</ul>