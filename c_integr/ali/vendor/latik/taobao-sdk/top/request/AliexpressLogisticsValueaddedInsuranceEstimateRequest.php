<?php
/**
 * TOP API: aliexpress.logistics.valueadded.insurance.estimate request
 * 
 * @author auto create
 * @since 1.0, 2020.08.05
 */
class AliexpressLogisticsValueaddedInsuranceEstimateRequest
{
	/** 
	 * 保额，单位：美金（分）
	 **/
	private $insuranceCoverage;
	
	/** 
	 * 解决方案Code
	 **/
	private $solutionCode;
	
	/** 
	 * 交易单ID
	 **/
	private $tradeOrderId;
	
	private $apiParas = array();
	
	public function setInsuranceCoverage($insuranceCoverage)
	{
		$this->insuranceCoverage = $insuranceCoverage;
		$this->apiParas["insurance_coverage"] = $insuranceCoverage;
	}

	public function getInsuranceCoverage()
	{
		return $this->insuranceCoverage;
	}

	public function setSolutionCode($solutionCode)
	{
		$this->solutionCode = $solutionCode;
		$this->apiParas["solution_code"] = $solutionCode;
	}

	public function getSolutionCode()
	{
		return $this->solutionCode;
	}

	public function setTradeOrderId($tradeOrderId)
	{
		$this->tradeOrderId = $tradeOrderId;
		$this->apiParas["trade_order_id"] = $tradeOrderId;
	}

	public function getTradeOrderId()
	{
		return $this->tradeOrderId;
	}

	public function getApiMethodName()
	{
		return "aliexpress.logistics.valueadded.insurance.estimate";
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
