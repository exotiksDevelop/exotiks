<?php
/**
 * TOP API: taobao.tae.delivery.info.delete request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:20
 */
class TaeDeliveryInfoDeleteRequest
{
	/** 
	 * 外部ISV记录主键
	 **/
	private $outId;
	
	private $apiParas = array();
	
	public function setOutId($outId)
	{
		$this->outId = $outId;
		$this->apiParas["out_id"] = $outId;
	}

	public function getOutId()
	{
		return $this->outId;
	}

	public function getApiMethodName()
	{
		return "taobao.tae.delivery.info.delete";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->outId,"outId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
