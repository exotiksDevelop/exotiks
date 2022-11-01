<?php

/**
 * List for multi language description. To learn how to set this field, please refer to the document:https://developers.aliexpress.com/en/doc.htm?docId=108976&docType=1
 * @author auto create
 */
class SingleLanguageDescriptionDto
{
	
	/** 
	 * Support: en(English) ru(Russian) es(Spanish) fr(French) it(Italian) tr(Turkish) pt(Portuguese) de(German) nl(Dutch) in(Indonesian) ar(Arabic) ja(Japanese) ko(Korean) th(Thai) vi(Vietnamese) iw(Hebrew)
	 **/
	public $language;
	
	/** 
	 * mobile detail for  this language, please check the format here https://developers.aliexpress.com/en/doc.htm?docId=109534&docType=1
	 **/
	public $mobile_detail;
	
	/** 
	 * web detail for this language, please check the format here: https://developers.aliexpress.com/en/doc.htm?docId=109534&docType=1
	 **/
	public $web_detail;	
}
?>