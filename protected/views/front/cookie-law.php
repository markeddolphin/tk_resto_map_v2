

<?php if(!empty($cookie_msg_text)):?>
<div class="cookie-wrap">
<a href="javascript:;" class="cookie-close"><i class="ion-ios-close-empty"></i></a>
  <div class="container-medium">
    <div class="row">
      <div class="col-sm-7">
         <p><?php echo $cookie_msg_text;?></p>
      </div> <!--col-->
      <div class="col-sm-5 top5">
        <a href="javascript:;" class="rounded orange-button medium accept-cookie">
        <?php echo $cookie_accept_text?>
        </a>
         <a href="<?php echo FunctionsV3::prettyUrl($cookie_info_link)?>" target="_blank" class="rounded green-button medium">
        <?php echo $cookie_info_text?>
        </a>
      </div> <!--col-->
    </div> <!--row-->
  </div> <!--container-->
</div> <!--cookie-wrap-->
<?php endif;?>