<?php
/**
 * TOP API: taobao.dd.item.cate.get request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:20
 */
class DdItemCateGetRequest
{
	/** 
	 * 门店id
	 **/
	private $outStoreid;
	
	/** 
	 * 数据归属淘宝账号id
	 **/
	private $sellerUsernick;
	
	/** 
	 * 淘宝菜品分类id
	 **/
	private $tbcateid;
	
	private $apiParas = array();
	
	public function setOutStoreid($outStoreid)
	{
		$this->outStoreid = $outStoreid;
		$this->apiParas["out_storeid"] = $outStoreid;
	}

	public function getOutStoreid()
	{
		return $this->outStoreid;
	}

	public function setSellerUsernick($sellerUsernick)
	{
		$this->sellerUsernick = $sellerUsernick;
		$this->apiParas["seller_usernick"] = $sellerUsernick;
	}

	public function getSellerUsernick()
	{
		return $this->sellerUsernick;
	}

	public function setTbcateid($tbcateid)
	{
		$this->tbcateid = $tbcateid;
		$this->apiParas["tbcateid"] = $tbcateid;
	}

	public function getTbcateid()
	{
		return $this->tbcateid;
	}

	public function getApiMethodName()
	{
		return "taobao.dd.item.cate.get";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->outStoreid,"outStoreid");
		RequestCheckUtil::checkNotNull($this->sellerUsernick,"sellerUsernick");
		RequestCheckUtil::checkNotNull($this->tbcateid,"tbcateid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
