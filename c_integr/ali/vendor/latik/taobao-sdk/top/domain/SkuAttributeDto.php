<?php

/**
 * sku attribute list. Some categories don't have sku attributes, then sku_attributes_list should be empty.
 * @author auto create
 */
class SkuAttributeDto
{
	
	/** 
	 * To obtain the available sku attribute names under a specific category, please check API: aliexpress.solution.sku.attribute.query
	 **/
	public $sku_attribute_name;
	
	/** 
	 * sku attribute value, which could be obtained through aliexpress.solution.sku.attribute.query or customized by sellers. When customized by sellers, do not include these 4 symbols #:=,
	 **/
	public $sku_attribute_value;
	
	/** 
	 * Image that will represent the variation of the product. The url can point to a seller's server or to AliExpress photobank. In order to obtain more information about the photobank and how to upload images, please visit the following page: https://developers.aliexpress.com/en/doc.htm?docId=30186&docType=2
	 **/
	public $sku_image;	
}
?>