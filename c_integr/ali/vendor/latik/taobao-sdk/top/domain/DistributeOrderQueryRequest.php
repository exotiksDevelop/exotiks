<?php

/**
 * API请求入参
 * @author auto create
 */
class DistributeOrderQueryRequest
{
	
	/** 
	 * 分销订单号
	 **/
	public $distribute_order_id;
	
	/** 
	 * 分销订单更新开始时间, 格式: yyyy-MM-dd HH:mm:ss, 如2020-06-10 00:00:00 倘若时间维度未精确到时分秒，故该时间条件筛选不许生效。此入参时间为美国太平洋时间.   若传入交易订单号或分销订单号, 此参数可不传.
	 **/
	public $modified_time_begin;
	
	/** 
	 * 分销订单更新结束时间, 格式: yyyy-MM-dd HH:mm:ss, 如2020-06-10 00:00:00 倘若时间维度未精确到时分秒，故该时间条件筛选不许生效。此入参时间为美国太平洋时间.  若传入交易订单号或分销订单号, 此参数可不传.
	 **/
	public $modified_time_end;
	
	/** 
	 * 页码, 默认1
	 **/
	public $page;
	
	/** 
	 * 分页大小，最大长度50，如果不传或者小于等于0，默认10
	 **/
	public $page_size;
	
	/** 
	 * 销售市场, 必填
	 **/
	public $sale_market;
	
	/** 
	 * 销售主单号
	 **/
	public $sale_order_id;	
}
?>