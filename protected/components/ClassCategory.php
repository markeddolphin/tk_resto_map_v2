<?php
class ClassCategory
{
	static $admin_cat_id='999999';
			
	public static function autoInsertCategory($mtid='')
	{
		$db=new DbExt;
		$auto_add=getOptionA('merchant_category_auto_add');
		if($auto_add==1){
			if($res=self::getAdminCategory()){
				foreach ($res as $val) {
					$params=$val;
					unset($params['cat_id']);
					$params['merchant_id']=$mtid;
					$params['parent_cat_id']=$val['cat_id'];
					$db->insertData("{{category}}",$params);
				}
			}
		}
	}
	
	public static function getAdminCategory()
	{
		$db=new DbExt;
		$stmt="SELECT * FROM
		{{category}}
		WHERE
		merchant_id=".q(self::$admin_cat_id)."
		ORDER BY sequence ASC
		";
		if($res=$db->rst($stmt)){
		   return $res;
		}
		return false;
	}
	
	public static function autoAddCategoryToMerchant($cat_id='')
	{
		if ( $cat_info=Yii::app()->functions->getCategory($cat_id)){
			$db=new DbExt;
			$stmt="SELECT merchant_id FROM
			{{merchant}}		
			ORDER BY merchant_id ASC
			";
			if($res=$db->rst($stmt)){
			   foreach ($res as $val) {			      
			      $params=$cat_info;
			      $params['merchant_id']=$val['merchant_id'];
			      $params['parent_cat_id']=$cat_id;
			      unset($params['cat_id']);
			      $db->insertData("{{category}}",$params);
			   }
			}
		}
		return false;
	}
	
	public static function updateCategoryMerchant($cat_id='',$params='')
	{
		if ( $cat_info=Yii::app()->functions->getCategory($cat_id)){
			$fields='';			
			if(is_array($params) && count($params)>=1){
				$db=new DbExt;
				unset($params['merchant_id']);
				foreach ($params as $key=>$val) {
					$fields.=" $key=".q($val).",";
				}
				$fields=substr($fields,0,-1);
				$stmt="UPDATE 
				{{category}}
				SET $fields
				WHERE parent_cat_id=".q($cat_id)."
				";				
				$db->qry($stmt);
			}
		}
	}
	
	public static function deleteCategory($cat_id='')
	{
		$db=new DbExt;
		$stmt="DELETE FROM {{category}} WHERE parent_cat_id=".q($cat_id)." ";
		$db->qry($stmt);
	}
			
} /*end class*/