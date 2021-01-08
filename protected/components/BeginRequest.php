<?php
class BeginRequest extends CBehavior {
	
	public function attach($owner) {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }
 
    public function handleBeginRequest($event) {        
     	 $app = Yii::app();
     	 $user = $app->user;
     	 dump($_GET); die();
     	 if (isset($_GET['lang'])){
     	 	$app->language = $_GET['lang'];
     	 	$app->user->setState('lang', $_GET['lang']);
     	    $cookie = new CHttpCookie('_lang', $_GET['lang']);
            $cookie->expire = time() + (60*60*24*365); // (1 year)
            Yii::app()->request->cookies['lang'] = $cookie;            
     	 } elseif ( $app->user->hasState('lang') ){
     	 	 $app->language = $app->user->getState('lang');
     	 } elseif ( isset(Yii::app()->request->cookies['lang']) ){
     	 	$app->language = Yii::app()->request->cookies['lang']->value;
     	 }
    }
    	
}