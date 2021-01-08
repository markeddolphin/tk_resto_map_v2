<?php
class DbExt
{			
	public function rst($sql='')
	{		
		//Yii::app()->db->active = false;		
		if (!empty($sql)){
			$connection=Yii::app()->db;
		    $rows=$connection->createCommand($sql)->queryAll();
		    if (is_array($rows) && count($rows)>=1){
		    	return $rows;
		    } else return false;
		} else return false;
	}
	
	public function qry($sql='')
	{		
		if (!empty($sql)){			
			if (Yii::app()->db->createCommand($sql)->query()){
			    return true;
		    } else return false;
		} else return false;
	}
	
	
	public function insertData($table='' ,$data=array()){						
		$connection=Yii::app()->db;
		$command = Yii::app()->db->createCommand();
		if ($command->insert($table,$data)){
			return true;
		} 
		return false;
	}
	
	
	public function updateData($table='' ,$data=array() , $wherefield='', $whereval=''){						
		$connection=Yii::app()->db;
		$command = Yii::app()->db->createCommand();
		$res = $command->update($table , $data , 
               "$wherefield=:$wherefield" , array(":$wherefield"=> addslashes($whereval) ));
        if ($res){
        	return true;
        }
        return false;
	}	
		
}
/*END: Cdb*/