<?php

/**
 * 费用详细列表
 * @author auto create
 */
class OpenFeeDetailDto
{
	
	/** 
	 * 应支付费用
	 **/
	public $fee;
	
	/** 
	 * 应支付费用币种
	 **/
	public $fee_currency;
	
	/** 
	 * 详细费用类型，normal_delivery_fee：配送费，sms_service_fee
	 **/
	public $fee_detail_type;
	
	/** 
	 * 已支付费用
	 **/
	public $paid_fee;
	
	/** 
	 * 已支付费用币种
	 **/
	public $paid_fee_currency;	
}
?>