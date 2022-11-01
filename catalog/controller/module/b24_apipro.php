<?php
class ControllerModuleB24Apipro extends Controller {
	
	// post.order.history.add
	public function addOrder($order_id) {
		$this->load->model('module/b24_order');
		$order = $this->model_module_b24_order->getOrder($order_id);
		
		if (empty($order_id)){
 			return;
 		}
		
		$get_b24_order = $this->model_module_b24_order->getById($order_id);
		
		if(isset($order_id) && empty($get_b24_order)){
			$this->model_module_b24_order->addOrder($order_id);			
 		} else {
 			$this->model_module_b24_order->editOrder($order_id);
 		}
	}
	
	public function editOrder($order_id) {
		$this->load->model('module/b24_order');
		$this->model_module_b24_order->editOrder($order_id);
	}
	// post.customer.add
	public function addCustomer($customerId) {
		$this->load->model('module/b24_customer');
		$this->model_module_b24_customer->addCustomer($customerId);
	}
	
	// post.customer.edit
	public function editCustomer($customerId) {
		$this->load->model('module/b24_customer');
		$this->model_module_b24_customer->editCustomer($customerId);
	}
	
	// post.customer.add.address
	public function addAddress($addressId) {
		$this->load->model('module/b24_customer');
		if ($this->isMainAddress($addressId)) {
			$this->model_module_b24_customer->editCustomerAddress($addressId);
		}
	}

	// post.customer.edit.address
	public function editAddress($addressId) {
		$this->load->model('module/b24_customer');	
		if ($this->isMainAddress($addressId)) {
			$this->model_module_b24_customer->editCustomerAddress($addressId);
		}
	}

	public function isMainAddress($addressId) {
		$this->load->model('account/customer');
		$customer = $this->model_account_customer->getCustomer($this->customer->getId());
		$currentAddressId = intval($customer['address_id']);
		return $currentAddressId === intval($addressId);
	}
}