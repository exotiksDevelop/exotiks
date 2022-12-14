<?php
/**
 * TOP API: taobao.wlb.waybill.i.search request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:20
 */
class WlbWaybillISearchRequest
{
	/** 
	 * 查询网点信息
	 **/
	private $waybillApplyRequest;
	
	private $apiParas = array();
	
	public function setWaybillApplyRequest($waybillApplyRequest)
	{
		$this->waybillApplyRequest = $waybillApplyRequest;
		$this->apiParas["waybill_apply_request"] = $waybillApplyRequest;
	}

	public function getWaybillApplyRequest()
	{
		return $this->waybillApplyRequest;
	}

	public function getApiMethodName()
	{
		return "taobao.wlb.waybill.i.search";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->waybillApplyRequest,"waybillApplyRequest");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
