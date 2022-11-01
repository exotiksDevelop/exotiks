<?php
require_once('../config.php');
require_once('lib/db.php');
require_once('lib/socnetauth2.php');
require_once('lib/gmail/src/Google/Client.php');
require_once('lib/gmail/src/Google/Config.php');
require_once('lib/gmail/src/Google/Auth/Abstract.php');
require_once('lib/gmail/src/Google/Auth/OAuth2.php');
require_once('lib/gmail/src/Google/Service.php');
require_once('lib/gmail/src/Google/Exception.php');
require_once('lib/gmail/src/Google/Auth/Exception.php');


require_once('lib/gmail/src/Google/Model.php');
require_once('lib/gmail/src/Google/Utils.php');
require_once('lib/gmail/src/Google/IO/Abstract.php');
require_once('lib/gmail/src/Google/IO/Curl.php');
require_once('lib/gmail/src/Google/Http/Request.php');
require_once('lib/gmail/src/Google/Http/CacheParser.php');
require_once('lib/gmail/src/Google/Service/Resource.php');
require_once('lib/gmail/src/Google/Service/Oauth2.php');
session_start();
if( ( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ) || 
			!empty($_SERVER['HTTPS']) )
	$protokol = 'https://';
else
	$protokol = 'http://';
$SocAuth = new SocAuth();
$SocAuth->setGroupName();

/* start r3 */
$IS_DEBUG = $SocAuth->get_config_param('socnetauth2_gmail_debug');

if( !$SocAuth->get_config_param('socnetauth2_gmail_status') )
{
	$url = "Location: ".$_SERVER['HTTP_REFERER'];
	
	if($IS_DEBUG)
	{
		if( strstr($url, "?") )
		{
			$url .= '&error=1';
		}
		else
		{
			$url .= '?error=1';
		}
	}
	
	header($url);
	exit();
}
/* end r3 */


if( !empty($_GET['first']) )
{
	$STATE = 'gmail_socnetauth2_'.rand();
	
	$CURRENT_URI = $_SERVER['HTTP_REFERER'];
	
	$socnetauth2_shop_folder = $SocAuth->get_config_param('socnetauth2_shop_folder');
	if( $socnetauth2_shop_folder ) $socnetauth2_shop_folder = '/'.$socnetauth2_shop_folder;
	
	$REDIRECT_URI = $protokol.$_SERVER['HTTP_HOST'].$socnetauth2_shop_folder.'/socnetauth2/gmail.php';
	
	if($IS_DEBUG)
	{
		echo "M1: ".$REDIRECT_URI."<hr>";
	}
	
	$CLIENT_ID = $SocAuth->get_config_param('socnetauth2_gmail_client_id');
	$CLIENT_SECRET = $SocAuth->get_config_param('socnetauth2_gmail_client_secret');
	
		
	
			
	$SocAuth->setRecord($STATE, $CURRENT_URI);
	setcookie("gm_state", $STATE);
	
	
	$client = new Google_Client();
	$client->setClientId($CLIENT_ID);
	$client->setClientSecret($CLIENT_SECRET);
	$client->setRedirectUri($REDIRECT_URI);
	#$client->addScope("https://www.googleapis.com/auth/urlshortener");
	$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
	$client->addScope("https://www.googleapis.com/auth/userinfo.email");
	
	$url = $client->createAuthUrl().'&';

	
	header("Location: ".$url);
	
	exit();
}

if( !empty($_GET['error']) && !empty( $_COOKIE['gm_state'] ) &&
	$recordData = $SocAuth->getRecord( $_COOKIE['gm_state'] ) )
{
	if($IS_DEBUG)
	{
		if( strstr($recordData['redirect'], "?") )
		{
			$recordData['redirect'] .= '&error=2';
		}
		else
		{
			$recordData['redirect'] .= '?error=2';
		}
	}
	
	
	header("Location: ".$recordData['redirect']);
}

if( !empty( $_GET['code'] ) && !empty( $_COOKIE['gm_state'] ) &&
	$recordData = $SocAuth->getRecord( $_COOKIE['gm_state'] ) )
{
	$CODE = $_GET['code'];
	
	if($IS_DEBUG)
	{
		echo "M2<hr>";
	}
	
	$CURRENT_URI = $recordData['redirect'];
	$CURRENT_URI = str_replace("?socnetauth2close=1", "", $CURRENT_URI);
	$CURRENT_URI = str_replace("&socnetauth2close=1", "", $CURRENT_URI);
	
	
	$socnetauth2_shop_folder = $SocAuth->get_config_param('socnetauth2_shop_folder');
	if( $socnetauth2_shop_folder ) $socnetauth2_shop_folder = '/'.$socnetauth2_shop_folder;
	$REDIRECT_URI = $protokol.$_SERVER['HTTP_HOST'].$socnetauth2_shop_folder.'/socnetauth2/gmail.php';
	
	
	if($IS_DEBUG)
	{
		echo "M3: ".$REDIRECT_URI."<hr>";
	}
	
	$CLIENT_ID = $SocAuth->get_config_param('socnetauth2_gmail_client_id');
	$CLIENT_SECRET = $SocAuth->get_config_param('socnetauth2_gmail_client_secret');
	
	
	
	$client = new Google_Client();
	$client->setClientId($CLIENT_ID);
	$client->setClientSecret($CLIENT_SECRET);
	$client->setRedirectUri($REDIRECT_URI);
	#$client->addScope("https://www.googleapis.com/auth/urlshortener");
	$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
	$client->addScope("https://www.googleapis.com/auth/userinfo.email");

	$service = new Google_Service_Oauth2($client);
	
	$client->authenticate($_GET['code']);
	#$SocAuth->setSessionData('access_token'] = $client->getAccessToken();
	#$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	#header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
	
	//$client->setAccessToken($SocAuth->setSessionData('access_token']);
	
	if( !$client->getAccessToken() ) exit('error1');
	
	$data = json_decode($client->getAccessToken(), 1);
	
	if( $IS_DEBUG ) echo "M4<hr>";
	
	if( !empty($data['access_token']) )
	{	
		$graph_url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&access_token='.$data['access_token'];
		
		if( $IS_DEBUG ) echo "M5: ".$graph_url."<hr>";
		
		if( extension_loaded('curl') )
		{
			$c = curl_init($graph_url);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
			$json = curl_exec($c);
			curl_close($c);
		}
		else
		{
			$json = file_get_contents($graph_url);
		}
		
			/*				
				id=>100000402380563
				name=>Petrov Konstantin
				first_name=>Petrov
				last_name=>Konstantin
				link=>http://www.gmail.com/petrov.konstantin
				username=>petrov.konstantin
				email=>kin208@gmail.com
				timezone=>6
				locale=>en_US
				verified=>1
				updated_time=>2012-02-11T12:39:00+0000
			*/
		
		if( $IS_DEBUG ) echo "M7: ".$json."<hr>";
		
		$userdata = json_decode($json, TRUE);
		
		
		$arr = $userdata;
		
		$provider = 'gmail';
		
		$arr = array(
			'identity' => $arr['id'],
			'firstname' => $arr['given_name'],
			'lastname'  => $arr['family_name'],
			'email'     => $arr['email'],
			'telephone'	=> ''
		);
		
		$data = array(
			'identity'  => $arr['identity'],
			'link' 		=> '',
			'firstname' => '',
			'lastname'  => '',
			'email'     => '',
			'telephone'	=> '',
			'data'		=> serialize($arr),
			'provider'  => $provider
		);
		
		if( !empty( $arr['firstname'] ) )
		{
			$data['firstname'] = $arr['firstname'];
		}
		
		if( !empty( $arr['lastname'] ) )
		{
			$data['lastname'] = $arr['lastname'];
		}
		
		if( !empty( $arr['email'] ) )
		{
			$data['email'] = $arr['email'];
		}
		
		if( !empty( $arr['telephone'] ) )
		{
			$data['telephone'] = $ar['telephone'];
		}
		
		$data['company'] = '';
		$data['address_1'] = '';
		$data['postcode'] = '';
		$data['city'] = '';
		$data['zone'] = '';
		$data['country'] = '';
		
		///////////////////////////////////////
		
		
		$SocAuth->checkDB();
		
		if( $customer_id = $SocAuth->checkNew($data) )
		{
			if( $SocAuth->get_config_param('socnetauth2_dobortype') != 'every' )
			{
				$SocAuth->setSessionData('customer_id', $customer_id);
				$SocAuth->setSessionData('socnetauth2_confirmdata_show', 0);
				
				if( $IS_DEBUG ) exit( "END-1 ".$CURRENT_URI."<hr>");
				header("Location: ".$CURRENT_URI ); 
			}
			else
			{
				if( $confirm_data = $SocAuth->isNeedConfirm( $data ) )
				{
					$data['customer_id'] = $customer_id;
					$confirm_data['data'] = $data;
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize($confirm_data));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					if( $IS_DEBUG ) exit( "END-2 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
				else
				{
					$SocAuth->setSessionData('customer_id', $customer_id);
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 0);
					
					if( $IS_DEBUG ) exit( "END-3 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
			}
		}
		else
		{
			$confirm_data = $SocAuth->isNeedConfirm( $data );
			
			if( !$SocAuth->get_config_param('socnetauth2_email_auth') || $SocAuth->get_config_param('socnetauth2_email_auth') == 'none' )
			{
				if( $confirm_data )
				{
					$confirm_data['data'] = $data;
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize($confirm_data));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					if( $IS_DEBUG ) exit( "END-4 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
				else
				{
					$SocAuth->setSessionData('socnetauth2_confirmdata', '');
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', '');
				
					$customer_id = $SocAuth->addCustomer( $data );
					$SocAuth->setSessionData('customer_id', $customer_id);	
					
					if( $IS_DEBUG ) exit( "END-5 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
			}
			elseif( $SocAuth->get_config_param('socnetauth2_email_auth') == 'confirm'  )
			{
				// требуется добор данных
				if( $confirm_data )
				{
					$confirm_data['data'] = $data;
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize($confirm_data));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					if( $IS_DEBUG ) exit( "END-6 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
				// Получен E-mail и включено проверка email письмом
				elseif( !empty( $data['email'] ) && $SocAuth->checkByEmail($data, 0) )
				{
					include(DIR_SYSTEM.'library/mail.php');
					
					$SocAuth->sendConfirmEmail($data);
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize(array(1, 2, 3, 4, $data['email'], $data['identity'], $data['link'], $data['provider'], $data)));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					if( $IS_DEBUG ) exit( "END-7 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI );
				}
				//Получен e-mail и он уникальный
				elseif( empty( $data['email'] ) ||
					 ( !empty( $data['email'] ) && !$SocAuth->checkByEmail($data, 0) ) )
				{
					$SocAuth->setSessionData('socnetauth2_confirmdata', '');
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', '');
				
					$customer_id = $SocAuth->addCustomer( $data );
					$SocAuth->setSessionData('customer_id', $customer_id);	
				
					if( $IS_DEBUG ) exit( "END-8 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
			}
			elseif( $SocAuth->get_config_param('socnetauth2_email_auth') == 'noconfirm'  )
			{
				// требуется добор данных
				if( $confirm_data )
				{
					$confirm_data['data'] = $data;
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize($confirm_data));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					if( $IS_DEBUG ) exit( "END-9 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
				// Получен E-mail 
				elseif( !empty( $data['email'] ) && $customer_id = $SocAuth->checkByEmail($data, 1) )
				{
					$SocAuth->setSessionData('socnetauth2_confirmdata', '');
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', '');
					$SocAuth->setSessionData('customer_id', $customer_id);	
				
					if( $IS_DEBUG ) exit( "END-10 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI );
				}
				//Получен e-mail и он уникальный 
				elseif( empty( $data['email'] ) ||
					 ( !empty( $data['email'] ) && !$SocAuth->checkByEmail($data, 0) ) )
				{
					$SocAuth->setSessionData('socnetauth2_confirmdata', '');
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', '');
				
					$customer_id = $SocAuth->addCustomer( $data );
					$SocAuth->setSessionData('customer_id', $customer_id);	
				
					if( $IS_DEBUG ) exit( "END-11 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}	
			}
		}
		
	}
	
}
?>