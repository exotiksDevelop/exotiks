<?php
/**
 * TOP API: cainiao.global.solution.service.resource.query request
 * 
 * @author auto create
 * @since 1.0, 2020.07.11
 */
class CainiaoGlobalSolutionServiceResourceQueryRequest
{
	/** 
	 * 多语言信息
	 **/
	private $locale;
	
	/** 
	 * 商家信息
	 **/
	private $sellerParam;
	
	/** 
	 * 发件信息
	 **/
	private $senderParam;
	
	/** 
	 * 查询参数
	 **/
	private $solutionServiceResParam;
	
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

	public function setSellerParam($sellerParam)
	{
		$this->sellerParam = $sellerParam;
		$this->apiParas["seller_param"] = $sellerParam;
	}

	public function getSellerParam()
	{
		return $this->sellerParam;
	}

	public function setSenderParam($senderParam)
	{
		$this->senderParam = $senderParam;
		$this->apiParas["sender_param"] = $senderParam;
	}

	public function getSenderParam()
	{
		return $this->senderParam;
	}

	public function setSolutionServiceResParam($solutionServiceResParam)
	{
		$this->solutionServiceResParam = $solutionServiceResParam;
		$this->apiParas["solution_service_res_param"] = $solutionServiceResParam;
	}

	public function getSolutionServiceResParam()
	{
		return $this->solutionServiceResParam;
	}

	public function getApiMethodName()
	{
		return "cainiao.global.solution.service.resource.query";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->sellerParam,"sellerParam");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
