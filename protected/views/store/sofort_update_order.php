<?php
$DbExt=new DbExt;
if ($status=="untraceable"){
	$status="paid";
}
if ($status=="received"){
	$status="paid";
}
$params=array(
  'status'=>$status
);

$DbExt->updateData("{{order}}",$params,'order_id',$order_id);

$params_logs=array(
  'order_id'=>$order_id,
  'payment_type'=>"sofort",
  'payment_reference'=>$sofort_trans_id,
  'raw_response'=>json_encode($sofort_trans_id),
  'date_created'=>FunctionsV3::dateNow(),
  'ip_address'=>$_SERVER['REMOTE_ADDR']
);
$DbExt->insertData("{{payment_order}}",$params_logs);

/*POINTS PROGRAM*/ 
if (FunctionsV3::hasModuleAddon("pointsprogram")){
   PointsProgram::updatePoints($order_id);
}

/*Driver app*/
if (FunctionsV3::hasModuleAddon("driver")){
   Yii::app()->setImport(array(			
	  'application.modules.driver.components.*',
   ));
   Driver::addToTask($order_id);
}
	        
$this->redirect( Yii::app()->createUrl('/store/receipt',array(
  'id'=>$order_id
)) );
die();