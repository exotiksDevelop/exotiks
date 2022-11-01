<?php

class parser_json extends response_parser {
	
	public function getData() {
		return json_decode($this->data, TRUE);
	}
	
}

?>