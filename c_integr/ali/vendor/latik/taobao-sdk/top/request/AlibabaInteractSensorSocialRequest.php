<?php
/**
 * TOP API: alibaba.interact.sensor.social request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:20
 */
class AlibabaInteractSensorSocialRequest
{
	
	private $apiParas = array();
	
	public function getApiMethodName()
	{
		return "alibaba.interact.sensor.social";
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
