<?php

/**
 * 物流服务列表
 * @author auto create
 */
class OpenServiceParam
{
	
	/** 
	 * DOOR_PICKUP:上门揽收；SELF_POST:自寄；SELF_SEND:自送；UNREACHABLE_RETURN:不可达退回；
	 **/
	public $code;
	
	/** 
	 * 不同物流服务的扩展信息
	 **/
	public $features;	
}
?>