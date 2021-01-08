<?php
if ($verify){
	$this->renderPartial('/front/default-header',array(
	   'h1'=>t("Thank You!"),
	   'sub_text'=>t("your email address has been verified")
	));
} else {
	$this->renderPartial('/front/default-header',array(
	   'h1'=>t("Thank You for signing up"),
	   'sub_text'=>t("we have sent you email with verification")
	));
}
?>