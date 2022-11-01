<?php  

class parser_xml extends response_parser {
	
	public function getData() {
		return (strpos($this->data, '<?xml') === 0) ? new SimpleXMLElement($this->data) : '';
	}
	
}

?>