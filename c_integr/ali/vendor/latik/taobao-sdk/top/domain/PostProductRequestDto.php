<?php

/**
 * input param
 * @author auto create
 */
class PostProductRequestDto
{
	
	/** 
	 * Aliexpress leaf category ID obtained through https://developers.aliexpress.com/en/doc.htm?docId=46042&docType=2
	 **/
	public $aliexpress_category_id;
	
	/** 
	 * There are 4 types of how to fill in the content of each element in this attribute list. 1). When filling in the standard dropdown/multi-dropdown attributes, fill in "aliexpress_attribute_name_id" and "aliexpress_attribute_value_id"(For multi-dropdown, splitting them into multiple elements) 2). When filling in the attributes in which the value needs to be manually entered, fill in "aliexpress_attribute_name_id" and "attribute_value" in the element. 3). There exists a special kind of "aliexpress_attribute_value_id" of which the value represents for "Other". When encoutering this scenario, please fill in "aliexpress_attribute_name_id", "aliexpress_attribute_value_id" and "attribute_value". 4). Besides the three types mentioned above, if the seller would like to fully customized all the atttributes, fill in "attribute name" and "attribute_value" in the element.
	 **/
	public $attribute_list;
	
	/** 
	 * merchant's brand name
	 **/
	public $brand_name;
	
	/** 
	 * Deprecated. Please use aliexpress_category_id
	 **/
	public $category_id;
	
	/** 
	 * freight template ID. After the merchant has created an freight template in the backend: freighttemplate.aliexpress.com, the id could be obtained in the backend directly or thourgh the API: aliexpress.freight.redefining.listfreighttemplate
	 **/
	public $freight_template_id;
	
	/** 
	 * group id, you can get group list from aliexpress.product.productgroups.get
	 **/
	public $group_id;
	
	/** 
	 * indicate when the inventory of a specific product will be deducted. place_order_withhold: the inventory is deducted just after the order is placed by customer. payment_success_deduct: the stock is deducted after the payment is done successfully by the customer.
	 **/
	public $inventory_deduction_strategy;
	
	/** 
	 * Main image that represents the product. The url should be accesible and there is a meximum of 6 pictures. The url can point to a seller's server or to AliExpress photobank. In order to obtain more information about the photobank and how to upload images, please visit the following page: https://developers.aliexpress.com/en/doc.htm?docId=30186&docType=2
	 **/
	public $main_image_urls_list;
	
	/** 
	 * multi country price configuration
	 **/
	public $multi_country_price_configuration;
	
	/** 
	 * List for multi language description. To learn how to set this field, please refer to the document:https://developers.aliexpress.com/en/doc.htm?docId=108976&docType=1
	 **/
	public $multi_language_description_list;
	
	/** 
	 * List for multi language subject. To learn how to set this field, please refer to the document:https://developers.aliexpress.com/en/doc.htm?docId=108976&docType=1
	 **/
	public $multi_language_subject_list;
	
	/** 
	 * Package height measured in centimeters (cm). Maximum 700 cm, minumum: 0.01cm
	 **/
	public $package_height;
	
	/** 
	 * Package length, measured in centimeters (cm). Maximum 700 cm, minumum: 0.01cm
	 **/
	public $package_length;
	
	/** 
	 * Package width measured in centimeters (cm). Maximum 700 cm, minumum: 0.01cm
	 **/
	public $package_width;
	
	/** 
	 * aliexpress product Id
	 **/
	public $product_id;
	
	/** 
	 * service policy id, which could be set and obtained in the seller's background.
	 **/
	public $service_policy_id;
	
	/** 
	 * refer to the preparation period of merchant before the package could be dispatched to the customer.
	 **/
	public $shipping_lead_time;
	
	/** 
	 * merchant's size chart id, more used in the category of shoes and clothes.
	 **/
	public $size_chart_id;
	
	/** 
	 * If specified this field, all the previous skus will be replaced by the new skus.
	 **/
	public $sku_info_list;
	
	/** 
	 * Weight of the product, including package. Measured in Kilograms (Kg) with a maximum 500 and minumum 0.001
	 **/
	public $weight;	
}
?>