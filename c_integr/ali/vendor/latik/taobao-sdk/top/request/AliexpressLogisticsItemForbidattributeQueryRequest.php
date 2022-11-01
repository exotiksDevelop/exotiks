<?php
/**
 * TOP API: aliexpress.logistics.item.forbidattribute.query request
 * 
 * @author auto create
 * @since 1.0, 2020.09.25
 */
class AliexpressLogisticsItemForbidattributeQueryRequest
{
	/** 
	 * 查询商品物流属性入参
	 **/
	private $queryItemAttributeRequest;
	
	private $apiParas = array();
	
	public function setQueryItemAttributeRequest($queryItemAttributeRequest)
	{
		$this->queryItemAttributeRequest = $queryItemAttributeRequest;
		$this->apiParas["query_item_attribute_request"] = $queryItemAttributeRequest;
	}

	public function getQueryItemAttributeRequest()
	{
		return $this->queryItemAttributeRequest;
	}

	public function getApiMethodName()
	{
		return "aliexpress.logistics.item.forbidattribute.query";
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
