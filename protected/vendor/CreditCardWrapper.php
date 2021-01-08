<?php
class CreditCardWrapper{
		
	public static function encryptCard($card_number='')
	{
		$encryption_key=getOptionA('offline_cc_encryption_key');				
		$encrypted = SaferCrypto::encrypt($card_number, $encryption_key);
		return $encrypted;
	}
	
	public static function decryptCard($encrypted_card='')
	{				
		$encryption_key=getOptionA('offline_cc_encryption_key');			
		$decrypted = SaferCrypto::decrypt(trim($encrypted_card), $encryption_key);
		return $decrypted;		
	}
	
} /*end class*/