<?php
/**
 * TOP API: taobao.wangwang.abstract.deleteword request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:19
 */
class WangwangAbstractDeletewordRequest
{
	/** 
	 * 传入参数的字符集
	 **/
	private $charset;
	
	/** 
	 * 关键词，长度大于0<br /> 支持最大长度为：12<br /> 支持的最大列表长度为：12
	 **/
	private $word;
	
	private $apiParas = array();
	
	public function setCharset($charset)
	{
		$this->charset = $charset;
		$this->apiParas["charset"] = $charset;
	}

	public function getCharset()
	{
		return $this->charset;
	}

	public function setWord($word)
	{
		$this->word = $word;
		$this->apiParas["word"] = $word;
	}

	public function getWord()
	{
		return $this->word;
	}

	public function getApiMethodName()
	{
		return "taobao.wangwang.abstract.deleteword";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->word,"word");
		RequestCheckUtil::checkMaxLength($this->word,12,"word");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
