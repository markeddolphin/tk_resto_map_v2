<?php
$root=Yii::getPathOfAlias('webroot');
define('LOCK_DIR', $root.'/cronHelper/');
//define('LOCK_SUFFIX', '.lock');

class cronHelper {

	private static $pid;

	function __construct() {}

	function __clone() {}

	private static function isrunning() {
		if(function_exists('shell_exec')) {
			$pids = explode(PHP_EOL, `ps -e | awk '{print $1}'`);
			if(in_array(self::$pid, $pids))
				return TRUE;
			return FALSE;
			}
		return false;
	}

	public static function lock() {
		global $argv;
		
		if ( !file_exists(LOCK_DIR)){			
			mkdir(LOCK_DIR,777);
		}

		$lock_file = LOCK_DIR.$argv[0].LOCK_SUFFIX;
		
		/*CHECK IF FILE IS ALREADY EXIST*/
		if(file_exists($lock_file)) {
			$is_old = false;
			$time_1 = date('Y-m-d g:i:s a');
			$time_2 = date("Y-m-d g:i:s a", filemtime($lock_file));			
			$time_diff=Yii::app()->functions->dateDifference($time_2,$time_1);				
			if(is_array($time_diff) && count($time_diff)>=1){				
				if($time_diff['days']>0){
					$is_old=true;
				}
				if($time_diff['hours']>0){
					$is_old=true;
				}
				if($time_diff['minutes']>5){
					$is_old=true;
				}
			}
			if($is_old){						
				self::unlock();
			}
		}
		/*END CHECK*/

		if(file_exists($lock_file)) {
			//return FALSE;

			// Is running?
			self::$pid = file_get_contents($lock_file);
			if(self::isrunning()) {
				//dump("==".self::$pid."== Already in progress...");
				return FALSE;
			}
			else {
				//dump("==".self::$pid."== Previous job died abruptly...");
			}
		}

		self::$pid = getmypid();
		file_put_contents($lock_file, self::$pid);
		//dump("==".self::$pid."== Lock acquired, processing the job...");
		return self::$pid;
	}

	public static function unlock() {
		global $argv;

		$lock_file = LOCK_DIR.$argv[0].LOCK_SUFFIX;

		if(file_exists($lock_file))
			unlink($lock_file);

		//dump("==".self::$pid."== Releasing lock...");
		return TRUE;
	}

}