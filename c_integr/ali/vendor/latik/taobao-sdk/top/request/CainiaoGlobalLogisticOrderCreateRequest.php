<?php
/**
 * TOP API: cainiao.global.logistic.order.create request
 * 
 * @author auto create
 * @since 1.0, 2020.10.09
 */
class CainiaoGlobalLogisticOrderCreateRequest
{
	/** 
	 * 多语言
	 **/
	private $locale;
	
	/** 
	 * 订单参数
	 **/
	private $orderParam;
	
	private $apiParas = array();
	
	public function setLocale($locale)
	{
		$this->locale = $locale;
		$this->apiParas["locale"] = $locale;
	}

	public function getLocale()
	{
		return $this->locale;
	}

	public function setOrderParam($orderParam)
	{
		$this->orderParam = $orderParam;
		$this->apiParas["order_param"] = $orderParam;
	}

	public function getOrderParam()
	{
		return $this->orderParam;
	}

	public function getApiMethodName()
	{
		return "cainiao.global.logistic.order.create";
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
