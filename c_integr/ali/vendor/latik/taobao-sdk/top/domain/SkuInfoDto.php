<?php

/**
 * If specified this field, all the previous skus will be replaced by the new skus.
 * @author auto create
 */
class SkuInfoDto
{
	
	/** 
	 * discount price for the sku. discount_price should be cheaper than price.
	 **/
	public $discount_price;
	
	/** 
	 * stock. Maximum:999999, minumum:1
	 **/
	public $inventory;
	
	/** 
	 * price. Maximum:999999, minumum:0.01
	 **/
	public $price;
	
	/** 
	 * sku attribute list. Some categories don't have sku attributes, then sku_attributes_list should be empty.
	 **/
	public $sku_attributes_list;
	
	/** 
	 * Code for merchant's sku, important reference to maintain the relationship between merchant and Aliexpress's system.
	 **/
	public $sku_code;	
}
?>