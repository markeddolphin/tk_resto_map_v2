<?php
class ControllerCheck extends CBehavior
{
    public function attach($owner)
    {
        $owner->attachEventHandler('onBeginRequest', array($this, 'handleBeginRequest'));
    }

    public function handleBeginRequest($event)
    {
        $path = explode("/", Yii::app()->request->pathInfo);        
        dump($path);
        //die();
        /*if($path[0] != "notfound") {
            $redirectUrl = Yii::app()->createUrl('notfound');
            Yii::app()->request->redirect($redirectUrl);
            Yii::app()->end();
        }*/
    }
}