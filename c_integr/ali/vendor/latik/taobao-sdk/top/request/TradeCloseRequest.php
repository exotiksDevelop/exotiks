<?php
/**
 * TOP API: taobao.trade.close request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:19
 */
class TradeCloseRequest
{
	/** 
	 * 交易关闭原因。可以选择的理由有：
1.未及时付款
2、买家不想买了
3、买家信息填写错误，重新拍
4、恶意买家/同行捣乱
5、缺货
6、买家拍错了
7、同城见面交易
	 **/
	private $closeReason;
	
	/** 
	 * 主订单或子订单编号。
	 **/
	private $tid;
	
	private $apiParas = array();
	
	public function setCloseReason($closeReason)
	{
		$this->closeReason = $closeReason;
		$this->apiParas["close_reason"] = $closeReason;
	}

	public function getCloseReason()
	{
		return $this->closeReason;
	}

	public function setTid($tid)
	{
		$this->tid = $tid;
		$this->apiParas["tid"] = $tid;
	}

	public function getTid()
	{
		return $this->tid;
	}

	public function getApiMethodName()
	{
		return "taobao.trade.close";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->closeReason,"closeReason");
		RequestCheckUtil::checkNotNull($this->tid,"tid");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
