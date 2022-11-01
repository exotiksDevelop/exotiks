<?php

/**
 * 商品参数
 * @author auto create
 */
class OpenItemParam
{
	
	/** 
	 * 商品价格币种
	 **/
	public $currency;
	
	/** 
	 * 商品英文名称
	 **/
	public $english_name;
	
	/** 
	 * 商品高度
	 **/
	public $height;
	
	/** 
	 * 商品属性，cf_normal：普货、cf_has_battery：含电。
	 **/
	public $item_features;
	
	/** 
	 * 商品ID
	 **/
	public $item_id;
	
	/** 
	 * 商品长度
	 **/
	public $length;
	
	/** 
	 * 商品本地名称
	 **/
	public $local_name;
	
	/** 
	 * 商品数量
	 **/
	public $quantity;
	
	/** 
	 * 后台商品ID
	 **/
	public $sc_item_id;
	
	/** 
	 * sku
	 **/
	public $sku;
	
	/** 
	 * 商品总价
	 **/
	public $total_price;
	
	/** 
	 * 商品单价，单位结算币种最小单位，如人民币分
	 **/
	public $unit_price;
	
	/** 
	 * 商品重量，单位g
	 **/
	public $weight;
	
	/** 
	 * 商品宽度
	 **/
	public $width;	
}
?>