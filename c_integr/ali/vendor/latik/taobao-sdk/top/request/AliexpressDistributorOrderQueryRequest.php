<?php
/**
 * TOP API: aliexpress.distributor.order.query request
 * 
 * @author auto create
 * @since 1.0, 2020.07.01
 */
class AliexpressDistributorOrderQueryRequest
{
	/** 
	 * API请求入参
	 **/
	private $queryRequest;
	
	private $apiParas = array();
	
	public function setQueryRequest($queryRequest)
	{
		$this->queryRequest = $queryRequest;
		$this->apiParas["query_request"] = $queryRequest;
	}

	public function getQueryRequest()
	{
		return $this->queryRequest;
	}

	public function getApiMethodName()
	{
		return "aliexpress.distributor.order.query";
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
