<?php
/**
 * TOP API: taobao.dmp.table.update request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:20
 */
class DmpTableUpdateRequest
{
	/** 
	 * table定义
	 **/
	private $table;
	
	private $apiParas = array();
	
	public function setTable($table)
	{
		$this->table = $table;
		$this->apiParas["table"] = $table;
	}

	public function getTable()
	{
		return $this->table;
	}

	public function getApiMethodName()
	{
		return "taobao.dmp.table.update";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->table,"table");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
