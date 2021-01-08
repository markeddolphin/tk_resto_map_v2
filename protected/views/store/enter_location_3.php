
<div class="row" style="margin-top:30px;">
  <div class="col-md-3" style="margin-left:28%;">
   
   <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">        
         <?php echo CHtml::textField('location_postalcode','',array(
          'placeholder'=>t("Postal Code/Zip Code"),
          'class'=>"typhead_postalcode rounded-corner",
          'autocomplete'=>"off",
          'required'=>true
         ))?>
         </div>
         </div>
         </div>
  
  </div> <!--col-->  
  
  <div class="col-md-3">
    <button type="submit" class="green-button medium "><?php echo t("SHOW RESTAURANTS")?></button>
  </div>
  
</div> <!--row-->