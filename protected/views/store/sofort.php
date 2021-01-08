<?php
namespace Sofort\SofortLib;
//require "sofortlib/vendor/autoload.php";

spl_autoload_unregister(array('YiiBase','autoload'));
require "sofortlib/vendor/autoload.php";
spl_autoload_register(array('YiiBase','autoload'));

$configkey = "127203:310725:50cb54a3292c54828891bd7debd575dd";
$Sofortueberweisung = new Sofortueberweisung($configkey);

$Sofortueberweisung->setAmount(1);
$Sofortueberweisung->setCurrencyCode('EUR');
$Sofortueberweisung->setLanguageCode('EN');
$Sofortueberweisung->setReason('Testueberweisung', 'Verwendungszweck');
$Sofortueberweisung->setSuccessUrl('http://bastisapp.com/sucess', true); // i.e. http://my.shop/order/success
$Sofortueberweisung->setAbortUrl('http://bastisapp.com/abort');

$Sofortueberweisung->setNotificationUrl('http://bastisapp.com/notification');

$Sofortueberweisung->sendRequest();

if($Sofortueberweisung->isError()) {
    // SOFORT-API didn't accept the data
    echo "failed:".$Sofortueberweisung->getError();
} else {
    // get unique transaction-ID useful for check payment status
    $transactionId = $Sofortueberweisung->getTransactionId();
    // buyer must be redirected to $paymentUrl else payment cannot be successfully completed!
    $paymentUrl = $Sofortueberweisung->getPaymentUrl();
    echo $paymentUrl;
    die();
    //header('Location: '.$paymentUrl);
}

echo 'finish';