<?php

/**
 * 分销订单列表
 * @author auto create
 */
class Module
{
	
	/** 
	 * 采购下单时间, 格式: yyyy-MM-dd HH:mm:ss, 时间为美国太平洋时间
	 **/
	public $distribute_order_create_time;
	
	/** 
	 * 采购币种
	 **/
	public $distribute_order_currency;
	
	/** 
	 * 分销订单号
	 **/
	public $distribute_order_id;
	
	/** 
	 * 订单更新时间, 格式: yyyy-MM-dd HH:mm:ss, 时间为美国太平洋时间
	 **/
	public $distribute_order_modified_time;
	
	/** 
	 * 采购总金额
	 **/
	public $distribute_order_total_price;
	
	/** 
	 * 商家中国主体名称
	 **/
	public $distributor;
	
	/** 
	 * 货源类型: 厂商直供/自营认证
	 **/
	public $item_source_type;
	
	/** 
	 * 采购付款状态: 未支付/支付中/支付成功/支付失败/支付已取消
	 **/
	public $payment_status;
	
	/** 
	 * 商家香港主体名称
	 **/
	public $purchaser;
	
	/** 
	 * 销售订单下单时间, 格式: yyyy-MM-dd HH:mm:ss, 时间为美国太平洋时间
	 **/
	public $sale_order_create_time;
	
	/** 
	 * 销售币种
	 **/
	public $sale_order_currency;
	
	/** 
	 * 前台交易主单号(AE/TW)
	 **/
	public $sale_order_id;
	
	/** 
	 * 销售订单支付时间, 格式: yyyy-MM-dd HH:mm:ss, 时间为美国太平洋时间
	 **/
	public $sale_order_pay_time;
	
	/** 
	 * 销售订单状态: 下单成功, 待支付/待发货/已发货/交易取消
	 **/
	public $sale_order_status;
	
	/** 
	 * 销售总金额
	 **/
	public $sale_order_total_price;
	
	/** 
	 * 子订单列表
	 **/
	public $sub_distribute_order_list;	
}
?>