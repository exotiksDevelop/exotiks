<?php
class ModelTotalRpcodonly2 extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) 
	{		
		if( !$this->config->get('russianpost2_status') )
		{
			return false;
		}
			
		if( $this->config->get('russianpost2_cod_script') != 'onlyshipping' )
		{
			return false;
		}
			
		if( $this->config->get('russianpost2_is_cod_included') != 'inmod' )
		{
			//return false;
		}
		
		
		if( empty($this->session->data['shipping_method']) || 
			!strstr($this->session->data['shipping_method']['code'], 'russianpost2') )
		{
			return false;
		}
		
		
		
		if( empty($this->session->data['shipping_method']['cost']) )
			return false;
		
		list($code1, $code2) = explode(".", $this->session->data['shipping_method']['code']);
		
		$russianpost2_methods = $this->custom_unserialize( $this->config->get('russianpost2_methods') );
	
		foreach($russianpost2_methods as $key=>$method)
		{
			if( $method['code'] != $code1 ) continue;
			
			foreach( $method['submethods'] as $submethod )
			{
				if( $submethod['code'] == $code1.'.'.$code2 )
				{
					if( empty($submethod['is_show_cod']) )
					{
						return;
					}
				}
			}
		}
		
		
		$ar = array();
		
		$ar = $this->custom_unserialize( $this->config->get('russianpost2_rpcodonly_title') );
			
		$title = html_entity_decode($ar[ $this->config->get('config_language_id') ]);
		
		$koef = 1;
		if( $this->config->get('russianpost2_cod_is_double') 	&&
			(float)$this->session->data['shipping_method']['cost'] * 2 <= $total['total']
		)
			$koef = 2;
		
		$cost = ( $total - ($this->session->data['shipping_method']['cost'] * $koef) ) * -1;
		
		$total_data[] = array( 
				'code'       => 'rpcodonly2',
        		'title'      => $title,
        		'text'       => $this->currency->format($cost),
        		'value'      => $cost,
				'sort_order' => $this->config->get('rpcodonly2_sort_order')
		);
			
		$total += $cost;
				
	}
	
	private function custom_unserialize($s)
	{
		if( is_array($s) ) return $s;
		
		if(
			stristr($s, '{' ) != false &&
			stristr($s, '}' ) != false &&
			stristr($s, ';' ) != false &&
			stristr($s, ':' ) != false
		){
			return unserialize($s);
		}else{
			return $s;
		}

	}
}
?>