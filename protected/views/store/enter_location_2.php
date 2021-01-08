
<div class="row" style="margin-top:30px;">
  <div class="col-md-3" style="margin-left:15%;">
   
     <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">                
         <?php echo CHtml::textField('location_state','',array(
          'placeholder'=>t("State"),
          'class'=>"typhead_state form-control",
          'autocomplete'=>"off",
          'required'=>true
         ))?>
         </div>
         </div>
         </div>
  
  </div> <!--col-->
  <div class="col-md-3">
  
       <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">                
         <?php echo CHtml::textField('location_city','',array(
           'placeholder'=>t("City"),
           'class'=>"typhead_city form-control",
           'autocomplete'=>"off",
           //'required'=>true
         ))?>
          </div>
         </div>
         </div>
  
  </div> <!--col-->
  
  <div class="col-md-3">
    <button type="submit" class="green-button medium "><?php echo t("SHOW RESTAURANTS")?></button>
  </div>
  
</div> <!--row-->