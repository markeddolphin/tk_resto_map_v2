<h3>
<?php echo t("Please run all cron jobs below in your server")?>
</h3>

<ul> 
 <li>
   <a target="_blank" href="<?php echo websiteUrl()?>/cron/processbroacast">
    <?php echo websiteUrl()?>/cron/processbroacast
   </a>
 </li>
 
 <li>
  <a target="_blank" href="<?php echo websiteUrl()?>/cron/processsms">
   <?php echo websiteUrl()?>/cron/processsms
  </a>
 </li>
 
 <li>
   <a target="_blank" href="<?php echo websiteUrl()?>/cron/processpayout">
    <?php echo websiteUrl()?>/cron/processpayout
  </a>
 </li>
 
 <li>
  <a target="_blank" href="<?php echo websiteUrl()?>/cron/fax">
  <?php echo websiteUrl()?>/cron/fax
  </a>
 </li> 
 
 <li>
  <a target="_blank" href="<?php echo websiteUrl()?>/cron/processemail">
  <?php echo websiteUrl()?>/cron/processemail
  </a>
 </li>
 
 <li>
 <p class="uk-text-muted" style="margin:0;padding:0;"><?php echo t("run this every hour")?></p>
  <a target="_blank" href="<?php echo websiteUrl()?>/cron/merchantexpired">
  <?php echo websiteUrl()?>/cron/merchantexpired
  </a>
 </li>
 
 <li>
 <p class="uk-text-muted" style="margin:0;padding:0;"><?php echo t("run this every hour")?></p>
  <a target="_blank" href="<?php echo websiteUrl()?>/cron/merchantnearexpiration">
  <?php echo websiteUrl()?>/cron/merchantnearexpiration
  </a>
 </li>
 
 <li>
  <a target="_blank" href="<?php echo websiteUrl()?>/cron/idleorder">
    <?php echo websiteUrl()?>/cron/idleorder
  </a>
 </li>
 
 <li style="margin-bottom:10px;margin-top:10px;">
  <p class="uk-text-muted" style="margin:0;padding:0;"><?php echo t("run this daily")?></p>
  <a target="_blank" href="<?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=1">
    <?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=1
  </a>
 </li>
 
 <li style="margin-bottom:10px;">
  <p class="uk-text-muted" style="margin:0;padding:0;"><?php echo t("run this weekly")?></p>
  <a target="_blank" href="<?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=7">
    <?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=7
  </a>  
 </li>
 
 <li style="margin-bottom:10px;">
  <p class="uk-text-muted" style="margin:0;padding:0;"><?php echo t("run this every 15 days")?></p>
  <a target="_blank" href="<?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=15">
    <?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=15
  </a>
 </li>
 
 <li style="margin-bottom:10px;">
  <p class="uk-text-muted" style="margin:0;padding:0;"><?php echo t("run this every end of the month")?></p>
  <a target="_blank" href="<?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=30">
    <?php echo websiteUrl()?>/invoicecron/generateinvoice?terms=30
  </a>
 </li>
 
 <li>
  <a target="_blank" href="<?php echo websiteUrl()?>/invoicecron/generatepdf">
    <?php echo websiteUrl()?>/invoicecron/generatepdf
  </a>
 </li>
 
</ul>


<p class="uk-text-muted">
<?php echo t("sample on how to cron jobs in your server")?><br/>
<a href="https://youtu.be/7lrNECQ5bvM" target="_blank"><?php echo t("click here")?></a>
</p>