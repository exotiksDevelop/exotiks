<?php

/**
 * 子订单列表
 * @author auto create
 */
class SubDistributeOrderList
{
	
	/** 
	 * 备注
	 **/
	public $comment;
	
	/** 
	 * 采购单价
	 **/
	public $distribute_unit_price;
	
	/** 
	 * 采购履约状态: 采购中/采购失败/采购成功, 待发货/已发货/已到仓/入仓失败/已出仓/已取消
	 **/
	public $fulfillment_status;
	
	/** 
	 * 前台商品ID
	 **/
	public $item_id;
	
	/** 
	 * 前台商品名称
	 **/
	public $item_title;
	
	/** 
	 * 物流公司
	 **/
	public $logistics_company;
	
	/** 
	 * 采购数量
	 **/
	public $quantity;
	
	/** 
	 * 销售单价
	 **/
	public $sale_unit_price;
	
	/** 
	 * 商品编码
	 **/
	public $seller_sku_code;
	
	/** 
	 * 供应商发货时间
	 **/
	public $ship_time;
	
	/** 
	 * 快递单号
	 **/
	public $tracking_number;	
}
?>