<?php
/**
 * TOP API: taobao.sellercenter.role.info.get request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:19
 */
class SellercenterRoleInfoGetRequest
{
	/** 
	 * 角色id<br /> 支持的最大列表长度为：100
	 **/
	private $roleId;
	
	private $apiParas = array();
	
	public function setRoleId($roleId)
	{
		$this->roleId = $roleId;
		$this->apiParas["role_id"] = $roleId;
	}

	public function getRoleId()
	{
		return $this->roleId;
	}

	public function getApiMethodName()
	{
		return "taobao.sellercenter.role.info.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->roleId,"roleId");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
