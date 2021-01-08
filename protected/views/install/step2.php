<?php 
$table_prefix=Yii::app()->db->tablePrefix;
$DbExt=new DbExt;

$path=Yii::getPathOfAlias('webroot')."/protected";
require_once($path.'/config/table_structure.php');
?>

<h3>Creating database tables...</h3>

<?php echo CHtml::beginForm( Yii::app()->createUrl('index.php/install/step3') ,
   'post'
);
?>


<p>
<?php $x=1;?>
<?php 
foreach ($tbl as $key=>$val) {    	    	
   echo "Creating table $key [OK]<br/>";   
   $DbExt->qry($val);
   $x++;
}
?>
</p>

<?php if($_SESSION['kr_install']==1):?>
<div class="panel panel-default">
<div class="panel-body">
 <button class="btn btn-success" type="submit" name="action">
   Next
 </button>
</div> 
</div>
<?php endif;?>

<?php echo CHtml::endForm() ; ?>