<?php

/**
 * 费用列表
 * @author auto create
 */
class OpenFeeDto
{
	
	/** 
	 * 币种
	 **/
	public $currency;
	
	/** 
	 * 费用详细列表
	 **/
	public $fee_detail_list;
	
	/** 
	 * 费用类型，POST_ESTIMATED_COST：预估费用
	 **/
	public $fee_type;
	
	/** 
	 * 总费用
	 **/
	public $total_fee;	
}
?>