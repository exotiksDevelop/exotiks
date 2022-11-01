<?php

/**
 * List for multi language subject. To learn how to set this field, please refer to the document:https://developers.aliexpress.com/en/doc.htm?docId=108976&docType=1
 * @author auto create
 */
class SingleLanguageTitleDto
{
	
	/** 
	 * Support: en(English) ru(Russian) es(Spanish) fr(French) it(Italian) tr(Turkish) pt(Portuguese) de(German) nl(Dutch) in(Indonesian) ar(Arabic) ja(Japanese) ko(Korean) th(Thai) vi(Vietnamese) iw(Hebrew)
	 **/
	public $language;
	
	/** 
	 * subject, maximum 128 characters.
	 **/
	public $subject;	
}
?>