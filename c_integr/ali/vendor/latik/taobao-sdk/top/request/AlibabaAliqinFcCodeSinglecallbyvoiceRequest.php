<?php
/**
 * TOP API: alibaba.aliqin.fc.code.singlecallbyvoice request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:20
 */
class AlibabaAliqinFcCodeSinglecallbyvoiceRequest
{
	
	private $apiParas = array();
	
	public function getApiMethodName()
	{
		return "alibaba.aliqin.fc.code.singlecallbyvoice";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
