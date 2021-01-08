<?php
class Cookie {

    const Session = null;
    const OneDay = 86400;
    const SevenDays = 604800;
    const ThirtyDays = 2592000;
    const SixMonths = 15811200;
    const OneYear = 31536000;
    const Lifetime = -1; // 2030-01-01 00:00:00


    public static function hasCookie($name)
    {
        return !empty(Yii::app()->request->cookies[$name]->value);
    }

    public static function getCookie($name)
    {
    	if (!empty(Yii::app()->request->cookies[$name]->value)){
            return Yii::app()->request->cookies[$name]->value;
    	}
    }

    public static function setCookie($name, $value)
    {
        setcookie ($name, $value , time() + (500*3600) , '/'); 
    }

    public static function removeCookie($name)
    {
        setcookie ($name, "", time()-(500*3600) , '/' );
    }

}