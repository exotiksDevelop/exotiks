<?php  
class app {
	
	static private $_instance;
	private $registry;
	
	private function __construct() {}
	
	static public function registry() {
		
		if (empty(self::$_instance)) {
			self::$_instance = new app();
		}
		
		return self::$_instance;
	}
	
	public function create(Registry &$registry) {
		if($this->registry === null || $registry === null) {
			$this->registry = $registry;
		}
	}
	
	public function get() {
		return $this->registry;
	}
	
	public function __get($key) {
		return $this->registry->get($key);
	}
	
	public function __set($key, $value) {
		$this->registry->set($key, $value);
	}
}

?>