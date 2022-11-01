<?php

/**
 * 返回实体,包含三个字段: 1、物流属性：ELECTRIC(带电)、SPECIAL(特货)、FORBID(违禁品)、NORMAL(普货) 2、二级物流属性 3、IATA法理解释
 * @author auto create
 */
class AeForbidTagResponse
{
	
	/** 
	 * IATA法理解释
	 **/
	public $iata_interpretation;
	
	/** 
	 * 物流属性 带电ELECTRIC、特货SPECIAL、违禁品FORBID、普货NORMAL
	 **/
	public $logistics_attribute;
	
	/** 
	 * 二级物流属性
	 **/
	public $secondary_logistics_attribute;	
}
?>