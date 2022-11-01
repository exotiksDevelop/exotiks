<?php

/**
 * 响应数据
 * @author auto create
 */
class OpenParcelOrderQueryResponse
{
	
	/** 
	 * 是否能组包
	 **/
	public $can_create_handover;
	
	/** 
	 * 关联的大包的编码
	 **/
	public $handover_content_code;
	
	/** 
	 * 关联的大包ID
	 **/
	public $handover_content_id;
	
	/** 
	 * 关联的交接单ID
	 **/
	public $handover_order_id;
	
	/** 
	 * 交接仓编码，快递揽收场景,大包交接目的地国际分拨
	 **/
	public $handover_warehouse_code;
	
	/** 
	 * 交接仓名称，快递揽收场景,大包交接目的地国际分拨
	 **/
	public $handover_warehouse_name;
	
	/** 
	 * 该小包是否已经组包
	 **/
	public $has_been_handover;	
}
?>