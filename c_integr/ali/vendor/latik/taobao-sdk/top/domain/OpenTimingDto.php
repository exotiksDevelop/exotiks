<?php

/**
 * 时效信息
 * @author auto create
 */
class OpenTimingDto
{
	
	/** 
	 * 展示文案
	 **/
	public $display_text;
	
	/** 
	 * 最快时效
	 **/
	public $fast_timing;
	
	/** 
	 * 最慢时效
	 **/
	public $slowest_timing;
	
	/** 
	 * 时效类型，ESTIMATE：预估时效，PROMISE：承诺时效
	 **/
	public $timing_type;	
}
?>