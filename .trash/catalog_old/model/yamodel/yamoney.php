<?php

Class ModelYamodelYamoney extends Model{
	public function checkSign($callbackParams, $password, $sid, $bKassa = false){
		if (isset($callbackParams['md5']) && $bKassa == true){
			$string = $callbackParams['action'].';'.$callbackParams['orderSumAmount'].';'.$callbackParams['orderSumCurrencyPaycash'].';'.$callbackParams['orderSumBankPaycash'].';'.$sid.';'.$callbackParams['invoiceId'].';'.$callbackParams['customerNumber'].';'.$password;
			if (strtoupper(md5($string))!==strtoupper($callbackParams['md5'])){
				$this->sendCode($callbackParams, $sid, '1', "");
				return false;
			}
		}else{
			$string = $callbackParams['notification_type'].'&'.$callbackParams['operation_id'].'&'.$callbackParams['amount'].'&'.$callbackParams['currency'].'&'.$callbackParams['datetime'].'&'.$callbackParams['sender'].'&'.$callbackParams['codepro'].'&'.$password.'&'.$callbackParams['label'];
			if (sha1($string) !== $callbackParams['sha1_hash']){
				header('HTTP/1.0 401 Unauthorized');
				return false;
			}
		}
		return true;
	}
	public function sendCode($callbackParams, $sid, $code, $message=''){
		if (!isset($callbackParams['md5'])) return false;
		header("Content-type: text/xml; charset=utf-8");
		$xml = '<?xml version="1.0" encoding="UTF-8"?>
			<'.$callbackParams['action'].'Response performedDatetime="'.date("c").'" code="'.$code.'" invoiceId="'.$callbackParams['invoiceId'].'" techMessage="'.$message.'" shopId="'.$sid.'"/>';
		echo $xml;
	}

	public static function log_save($logtext){
		$error_log = new Log('error.log');
		$error_log->write($logtext.PHP_EOL);
		$error_log = null;
	}
}

Class ModelExtensionYamodelYamoney extends ModelYamodelYamoney{}