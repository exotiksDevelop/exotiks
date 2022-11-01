<?php
/**
 * TOP API: taobao.vmarket.eticket.resend request
 * 
 * @author auto create
 * @since 1.0, 2015-01-20 12:44:20
 */
class VmarketEticketResendRequest
{
	/** 
	 * 码商ID，如果是码商，必须传，如果是信任卖家，不需要传
	 **/
	private $codemerchantId;
	
	/** 
	 * 订单编号
	 **/
	private $orderId;
	
	/** 
	 * 不需要上传二维码图片的码商请不要传，需要传入二维码的码商请先调用taobao.vmarket.eticket.qrcode.upload接口，将返回的img_filename文件名称作为参数（如果二维码不变的话，也可将将发码时传入二维码文件名作为参数传入），多个文件名用逗号隔开且与参数verify_codes按从左到有的顺序一一对应。
	 **/
	private $qrImages;
	
	/** 
	 * 安全验证token,回传淘宝发通知时发过来的token串
	 **/
	private $token;
	
	/** 
	 * 重新发送的验证码及可验证次数的列表，多个码之间用英文逗号分割，需要包含此订单所有可用的码（如果订单总的有10个码，可用的是5个，那么这里设置的是5个可用的码）
	 **/
	private $verifyCodes;
	
	private $apiParas = array();
	
	public function setCodemerchantId($codemerchantId)
	{
		$this->codemerchantId = $codemerchantId;
		$this->apiParas["codemerchant_id"] = $codemerchantId;
	}

	public function getCodemerchantId()
	{
		return $this->codemerchantId;
	}

	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;
		$this->apiParas["order_id"] = $orderId;
	}

	public function getOrderId()
	{
		return $this->orderId;
	}

	public function setQrImages($qrImages)
	{
		$this->qrImages = $qrImages;
		$this->apiParas["qr_images"] = $qrImages;
	}

	public function getQrImages()
	{
		return $this->qrImages;
	}

	public function setToken($token)
	{
		$this->token = $token;
		$this->apiParas["token"] = $token;
	}

	public function getToken()
	{
		return $this->token;
	}

	public function setVerifyCodes($verifyCodes)
	{
		$this->verifyCodes = $verifyCodes;
		$this->apiParas["verify_codes"] = $verifyCodes;
	}

	public function getVerifyCodes()
	{
		return $this->verifyCodes;
	}

	public function getApiMethodName()
	{
		return "taobao.vmarket.eticket.resend";
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function check()
	{
		
		RequestCheckUtil::checkNotNull($this->orderId,"orderId");
		RequestCheckUtil::checkNotNull($this->token,"token");
		RequestCheckUtil::checkNotNull($this->verifyCodes,"verifyCodes");
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
