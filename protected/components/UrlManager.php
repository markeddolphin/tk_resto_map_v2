<?php
class UrlManager extends CUrlManager
{
        public function createUrl($route,$params=array(),$ampersand='&')
        {        	        	
            if(preg_match('/[A-Z]/',$route)!==0)
            {            	
                $route=strtolower(preg_replace('/(?<=\\w)([A-Z])/','-\\1',$route)); 
            }    
            
            /*if(isset($_GET['lang'])){
            	if(!empty($_GET['lang'])){
            	   $params['lang']=$_GET['lang'];
            	}
            } */
            
            //dump("->".$route);
            return parent::createUrl($route,$params,$ampersand);
        }

        public function parseUrl($request)
        {               
            $route=parent::parseUrl($request);
            $route_original=$route;
            if(substr_count($route,'-')>0)
            {                            	
               $phpversion=phpversion();               
               if ($phpversion>=5.3){                	
               	  $route=lcfirst(str_replace(' ','',ucwords(str_replace('-',' ',$route))));
                  $route_original=lcfirst(str_replace(' ','x',ucwords(str_replace('-',' ',$route_original))));
               } else {
               	  $route=strtolower(str_replace(' ','',ucwords(str_replace('-',' ',$route))));
                  $route_original=strtolower(str_replace(' ','x',ucwords(str_replace('-',' ',$route_original))));
               }             
            }                    
                            
            //dump($route_original);
            if (preg_match("/admin/i",$route_original)) {            	
            
            } elseif (preg_match("/pagex/i",$route_original)){
            	$route='store/page';
            } elseif (preg_match("/menux/i",$route_original)){            	
            	$route='store/menu';
            }	
            //dump($route);
            return $route;
        }
        
        public function dump($data='')
        {
        	echo '<pre>';
        	print_r($data);
        	echo '</pre>';
        }
}