<?php
class ExcelFormat
{	
	
	var $_header;
	var $_data;
	var $_line;
	var $_filename;
	
	public function __construct($filename=''){		
		$this->_filename = $filename;
	}
			
	/**
	 * @param 
	 * @return 
	 */
	private function setHeader(){
				
        @header("Content-type: application/vnd.ms-excel");
        @header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                
		if (strstr($_SERVER['HTTP_USER_AGENT'],"IE")) {
			@header('Content-Disposition: inline; filename="'.$this->_filename.'"');
			@header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			@header('Pragma: public');
		} else {
			@header('Content-Disposition: attachment; filename="'.$this->_filename.'"');
			@header('Pragma: no-cache');
		}
	} /* END setHeader */
	
	
	
	
	/**	
	 *
	 * @param  $value
	 */
	public function addHeader($value=''){
		$this->_header[] = $value;
	}
	
	
	
	public function addHeaders($value=array()){
		$this->_header = $value;
	}
	
			
	/**		 
	 * @param $data array
	 * @return array
	 */
	public function setData($data=array() ){
		$this->_data = $data;
	}
	
	public function prepareExcel(){		
		
		if (count($this->_header)>=1 && is_array($this->_header)){
			foreach ($this->_header as $value) {
		        $this->_line .= "$value,";
			}
			$this->_line = substr($this->_line,0,-1);
			$this->_line = $this->_line."\n";			
		}
		
			
		$line = '';	
		
		if (is_array($this->_data) && count($this->_data)>=1){
			foreach ($this->_data as $key => $value) {			        
		        for ($i = 0; $i < count($value); $i ++) {
		        	//$line .="$value[$i], ";
		        	if (is_numeric($value[$i])) {
		        		$line .='="'.$value[$i].'",';
		        	} else $line .='"'.$value[$i].'",';		        	
		        }		
		        $line .="\n";        
			}			
		}  
		
	
		
		$this->setHeader();
		
		$this->_line = str_replace("\r", "", $this->_line);
		echo $this->_line . $line;
		
	} /* END prepareExcel */
	
	
	
	
	public function prepareExcel2(){		
		
		if (count($this->_header)>=1 && is_array($this->_header)){
			foreach ($this->_header as $value) {
		        $this->_line .= "$value,";
			}
			$this->_line = substr($this->_line,0,-1);
			$this->_line = $this->_line."\n";			
		}
		
			
		$line = '';	
		
		if (is_array($this->_data) && count($this->_data)>=1){
			foreach ($this->_data as $key => $value) {			        
				$xx=1;
		        for ($i = 0; $i < count($value); $i ++) {				        	
		        	//$value[$i]=str_replace(','," ",$value[$i]);
		        	if (count($value)==$xx):
		        	    //$line .="$value[$i]";
		        	    $line .='"'.$value[$i].'"';
		        	    else :
		        	    //$line .="$value[$i],";
		        	    $line .='"'.$value[$i].'",';
		        	endif;
		        	/*if (is_numeric($value[$i])) {
		        		$line .='="'.$value[$i].'",';
		        	} else $line .='"'.$value[$i].'",';*/		        	
		        	$xx++;
		        }		
		        $line .="\n";        
			}			
		}  
		
	
		
		$this->setHeader();
		
		$this->_line = str_replace("\r", "", $this->_line);
		echo $this->_line . $line;
		
	} /* END prepareExcel */
	
	private function damp($data=''){
		echo '<pre>'; print_r($data);echo '</pre>';
	}
	
	
} /* END Excel_Format */
?>