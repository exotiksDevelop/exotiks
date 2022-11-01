<?php

class Business_ru_api_lib {

	private $secret = '';
	
	private $app_id;
	private $token;
	private $address;
	
	public function __construct( $app_id, $token, $address ) {
		$this->app_id  = $app_id;
		$this->token   = $token;
		$this->address = $address;
	}
	
	public function setSecret( $secret ) {
		$this->secret = $secret;
	}
	public function setToken( $token ) {
		$this->token = $token;
	}
	
	public function deinstall() {
		$data = array();
		if( isset( $_GET[ 'app_id'  ] ) ) $data[ 'app_id'  ] = $_GET[ 'app_id'  ]; else return false;
		if( isset( $_GET[ 'app_psw' ] ) ) $data[ 'app_psw' ] = $_GET[ 'app_psw' ]; else return false;
		$params_string = 'app_id='.$data[ 'app_id' ];
		return MD5( $this->token.$this->secret.$params_string ) == $data[ 'app_psw' ];
	}
	
	public function request( $action, $model, $params = array() ) {
		if( isset( $params[ 'images' ] ) )
			if( is_array( $params[ 'images' ] ) )
				$params[ 'images' ] = json_encode( $params[ 'images' ] );
	
		$params[ 'app_id' ] = $this->app_id;
		ksort( $params );
		array_walk_recursive($params, function (&$val, $key) {
			if (is_null($val)) {
				$val = '';
			}
		});
		$params_string = http_build_query( $params );
		$params = array();
		$params[ 'app_psw' ] = MD5( $this->token.$this->secret.$params_string ); 
		
		$params_string .= '&'.http_build_query( $params );
		$url = $this->address."/api/rest/".$model.".json";
		
		$c = curl_init();
		
		if     ( $action == 'post' ) {
			curl_setopt( $c, CURLOPT_URL, $url );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $c, CURLOPT_POST, true );
			curl_setopt( $c, CURLOPT_POSTFIELDS, $params_string );		
		}
		else if( $action == 'get'  ) {
			curl_setopt( $c, CURLOPT_URL, $url.'?'.$params_string );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		}
		else if( $action == 'put'  ) {
			curl_setopt( $c, CURLOPT_URL, $url );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $c, CURLOPT_CUSTOMREQUEST, 'PUT' );
			curl_setopt( $c, CURLOPT_POSTFIELDS, $params_string );		
		}
		else if( $action == 'delete'  ) {
			curl_setopt( $c, CURLOPT_URL, $url );
			curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $c, CURLOPT_CUSTOMREQUEST, 'DELETE' );
			curl_setopt( $c, CURLOPT_POSTFIELDS, $params_string );		
		}
		
		$result = curl_exec( $c );

		$status_code = curl_getinfo( $c, CURLINFO_HTTP_CODE );
		curl_close( $c );

		if( $status_code == 200 ) {
			$result = json_decode( $result, true );
			$app_psw = $result[ 'app_psw' ];
			unset( $result[ 'app_psw' ] );		
			
			if( MD5( $this->token.$this->secret.json_encode( $result ) ) == $app_psw ) {
				$this->token = $result[ 'token' ]; 
				return( $result );
			}
			else
				return( array( 
					"status"     => "error", 
					"error_code" => "auth:1", 
					"error_text" => "Ошибка авторизации", 
				) );
		}
		else
			return( array( 
				"status"     => "error", 
				"error_code" => "http:".$status_code 
			) );
	}
	
	public function repair() {
		$params = array();
		$params[ 'app_id'  ] = $this->app_id;
		ksort( $params );
		$params_string = http_build_query( $params );
		$params = array();
		$params[ 'app_psw' ] = MD5( $this->secret.$params_string ); 
		
		$params_string .= '&'.http_build_query( $params );
		$url = $this->address."/api/rest/repair.json";
		
		$c = curl_init();
		curl_setopt( $c, CURLOPT_URL, $url.'?'.$params_string );
		curl_setopt( $c, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec( $c );
		$status_code = curl_getinfo( $c, CURLINFO_HTTP_CODE );
		curl_close( $c );
	
		if( $status_code == 200 ) {
			$result = json_decode( $result, true );
			$app_psw = $result[ 'app_psw' ];
			unset( $result[ 'app_psw' ] );		
			
			if( MD5( $this->secret.json_encode( $result ) ) == $app_psw ) {
				$this->token = $result[ 'token' ]; 
				return ( array( 
					"status" => "ok", 
					"token"  => $result[ 'token' ], 
				) );
			}
			else
				return( array( 
					"status"     => "error", 
					"error_code" => "auth:1", 
					"error_text" => "Ошибка авторизации", 
				) );
		}
		else
			return( array( 
				"status"     => "error", 
				"error_code" => "http:".$status_code 
			) );		
	}
}