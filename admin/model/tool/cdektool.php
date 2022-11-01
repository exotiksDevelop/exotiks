<?php
class ModelToolCdektool extends Model {

	public function check()
	{
		$license = $this->chechLicense();

		if($license['status'] == false) {
			$this->session->data['error'] = $license['message'];
			return false;
		} else {
			return true;
		}
	}

	public function checkInstalled($type, $name)
	{
		$this->load->model('extension/extension');

		$extensions = $this->model_extension_extension->getInstalled($type);

		if (!in_array($name, $extensions)) 
		{
			$installed = false;
			
			$this->model_extension_extension->install($type, $name);

			$this->load->model('user/user_group');

			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', $type . '/' . $name);
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', $type . '/' . $name);

			// Call install method if it exsits
			$this->load->controller($type . '/' . $name . '/install');
		}
	}

	public function chechLicense()
	{
		$this->load->model('setting/setting');
		$license_status = 'fail';
		$user = 'test';
		$password = 'test';
		
		$settings = $this->model_setting_setting->getSetting('cdekLicense');
		if(isset($settings['cdekLicense_user']) && isset($settings['cdekLicense_password']))
		{
			$user = $settings['cdekLicense_user'];
			$password = $settings['cdekLicense_password'];
			$license_status = $this->chechUser($user, $password);
			if($license_status == 'fail')
			{
				$license = array('status'=>false, 'message'=>'Пользователь '.$user.' не активен! <a href="http://cdek-souz.ru/users/registrate/" target="_blank">Зарегистрироваться</a> <br> <b>Перед установкой/настройкой модуля пожалуйста, ознакомьтесь с <a href="http://cdek.opencart.ru/documentation/" target="_blank">документацией</a></b>');
			}
			else
			{
				$license = array('status'=>true, 'message'=>'Лицензия '.$user.' работает нормально!');	
			}
		}
		else
		{
			$license = array('status'=>false, 'message'=>'Нет лицензии! <a href="http://cdek-souz.ru/users/registrate/" target="_blank">Зарегистрироваться</a> <br> <b>Перед установкой/настройкой модуля пожалуйста, ознакомьтесь с <a href="http://cdek.opencart.ru/documentation/" target="_blank">документацией</a></b>');
		}	

		return $license;
	}

	public function chechUser($user, $pass)
	{
		$licenseServers = array();

		$licenseServers[] = 'http://cdek-souz.ru/udata/users/userCheck/';
		$licenseServers[] = 'https://cdek.im/udata/users/userCheck';
		$licenseServers[] = 'http://cdek.opencart.ru/service/license/';

		$status = 'fail';

		foreach ($licenseServers as $licenseServer) {
			$response = $this->getCurl($licenseServer.'?m='.$user.'&p='.$pass.'');
			$status = $response['content'];
			
			if($status != 'fail')
			{
				return $status;
			}
		}

		return $status;
	}

	public function getCurl( $url )
	{
	    $options = array(
	        CURLOPT_RETURNTRANSFER => true,     // return web page
	        CURLOPT_HEADER         => false,    // don't return headers
	        CURLOPT_FOLLOWLOCATION => false,     // follow redirects, or false
	        CURLOPT_ENCODING       => "",       // handle all encodings
	        CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17", // who am i
	        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
	        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
	        CURLOPT_TIMEOUT        => 120,      // timeout on response
	        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
	        );
	    $ch      = curl_init( $url );
	    curl_setopt_array( $ch, $options );
	    $content = curl_exec( $ch );
	    $err     = curl_errno( $ch );
	    $errmsg  = curl_error( $ch );
	    $header  = curl_getinfo( $ch );
	    curl_close( $ch );
	    $header['errno']   = $err;
	    $header['errmsg']  = $errmsg;
	    $header['content'] = $content;
	    return $header;
	}
}