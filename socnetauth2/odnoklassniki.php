<?php
require_once('../config.php');
require_once('lib/db.php');
require_once('lib/socnetauth2.php');
session_start();

$SocAuth = new SocAuth();
if( ( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ) || 
			!empty($_SERVER['HTTPS']) )
	$protokol = 'https://';
else
	$protokol = 'http://';
$SocAuth->setGroupName();

/* start r3 */
$IS_DEBUG = $SocAuth->get_config_param('socnetauth2_odnoklassniki_debug');

if( !$SocAuth->get_config_param('socnetauth2_odnoklassniki_status') )
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
	$APPLICATION_ID = $SocAuth->get_config_param('socnetauth2_odnoklassniki_application_id');
	$socnetauth2_shop_folder = $SocAuth->get_config_param('socnetauth2_shop_folder');
	if( $socnetauth2_shop_folder ) $socnetauth2_shop_folder = '/'.$socnetauth2_shop_folder;
	
	$REDIRECT_URI = $protokol.$_SERVER['HTTP_HOST'].$socnetauth2_shop_folder.'/socnetauth2/odnoklassniki.php';
	
	$CURRENT_URI = $_SERVER['HTTP_REFERER'];
		
	$STATE = 'odnoklassniki_socnetauth2_'.rand();
	$SocAuth->setRecord($STATE, $CURRENT_URI);
		
	setcookie("od_state", $STATE);
		
	$url = 'http://connect.ok.ru/oauth/authorize?client_id='.
	$APPLICATION_ID.'&response_type=code&scope=VALUABLE_ACCESS,GET_EMAIL&redirect_uri='.urlencode($REDIRECT_URI);
	
	header("Location: ".$url);
}

if( !empty($_GET['error']) && !empty( $_COOKIE['od_state'] ) &&
	$recordData = $SocAuth->getRecord( $_COOKIE['od_state'] ) )
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

if( !empty( $_GET['code'] ) && !empty( $_COOKIE['od_state'] ) &&
	$recordData = $SocAuth->getRecord( $_COOKIE['od_state'] ) )
{
	if($IS_DEBUG)
	{
		echo "M2<hr>";
	}
	$CODE = $_GET['code'];
	
	$CURRENT_URI = $recordData['redirect'];
	$CURRENT_URI = str_replace("?socnetauth2close=1", "", $CURRENT_URI);
	$CURRENT_URI = str_replace("&socnetauth2close=1", "", $CURRENT_URI);
	
	
	$socnetauth2_shop_folder = $SocAuth->get_config_param('socnetauth2_shop_folder');
	if( $socnetauth2_shop_folder ) $socnetauth2_shop_folder = '/'.$socnetauth2_shop_folder;
	$REDIRECT_URI = $protokol.$_SERVER['HTTP_HOST'].$socnetauth2_shop_folder.'/socnetauth2/odnoklassniki.php';
	
	if($IS_DEBUG)
	{
		echo "M3: ".$REDIRECT_URI."<hr>";
	}
	
	
	$CLIENT_ID = $SocAuth->get_config_param('socnetauth2_odnoklassniki_application_id');
	$CLIENT_SECRET = $SocAuth->get_config_param('socnetauth2_odnoklassniki_secret_key');
	$CLIENT_PUBLIC = $SocAuth->get_config_param('socnetauth2_odnoklassniki_public_key');
	
	//code={code}&redirect_uri=http://mysite.com/oklogin&grant_type=authorization_code&client_id={client_id}&client_secret={secret_key}
	
	$POSTURL  = 'http://api.ok.ru/oauth/token.do';
	$POSTVARS = 'code='.$CODE.'&redirect_uri='.$REDIRECT_URI.'&grant_type=authorization_code'.
	'&client_id='.$CLIENT_ID.'&client_secret='.$CLIENT_SECRET;
	
	$ch = curl_init($POSTURL);
	curl_setopt($ch, CURLOPT_POST      ,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS    , $POSTVARS);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
	curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
	curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
	$response = curl_exec($ch);
	curl_close($ch);
 
	if( $IS_DEBUG ) echo "M4: ".$response."<hr>";
	
	$data = json_decode($response, true);
	
	if( !empty($data['access_token']) )
	{
		$SIGN = md5('application_key='.$CLIENT_PUBLIC.'method=users.getCurrentUser'.md5($data['access_token'].$CLIENT_SECRET));
		
		$graph_url = "http://api.ok.ru/fb.do?method=users.getCurrentUser".
		"&access_token=".$data['access_token'].
		"&application_key=".$CLIENT_PUBLIC.
		"&sig=".$SIGN;
		
		
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
		
		
		if( $IS_DEBUG ) echo "M7: ".$json."<hr>";
		
		$userdata = json_decode($json, TRUE);
		
		$arr = $userdata;
		
		$provider = 'odnoklassniki';

		if (isset($arr)&&isset($arr['uid'])&&isset($arr['first_name'])&&isset($arr['last_name'])&&isset($arr['email'])) {
			$arr = array(
				'identity' => $arr['uid'],
				'firstname' => $arr['first_name'],
				'lastname'  => $arr['last_name'],
				'email'     => $arr['email'],
				'telephone'	=> ''
			);
			
			$data = array(
				'identity'  => $arr['identity'],
				'link' 		=> "http://ok.ru/profile/".$arr['identity'],
				'firstname' => '',
				'lastname'  => '',
				'email'     => '',
				'telephone'	=> '',
				'data'		=> serialize($arr),
				'provider'  => $provider
			);
		} else {
			if( $IS_DEBUG ) echo "M100: User data is not full<hr>";
		}
		
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
				// ?????????????????? ?????????? ????????????
				if( $confirm_data )
				{
					$confirm_data['data'] = $data;
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize($confirm_data));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					if( $IS_DEBUG ) exit( "END-6 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
				// ?????????????? E-mail ?? ???????????????? ???????????????? email ??????????????
				elseif( !empty( $data['email'] ) && $SocAuth->checkByEmail($data, 0) )
				{
					include(DIR_SYSTEM.'library/mail.php');
					
					$SocAuth->sendConfirmEmail($data);
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize(array(1, 2, 3, 4, $data['email'], $data['identity'], $data['link'], $data['provider'], $data)));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					
					if( $IS_DEBUG ) exit( "END-7 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI );
				}
				//?????????????? e-mail ?? ???? ????????????????????
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
				// ?????????????????? ?????????? ????????????
				if( $confirm_data )
				{
					$confirm_data['data'] = $data;
					$SocAuth->setSessionData('socnetauth2_confirmdata', serialize($confirm_data));
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', 1);
					
					if( $IS_DEBUG ) exit( "END-9 ".$CURRENT_URI."<hr>");
					header("Location: ".$CURRENT_URI ); 
				}
				// ?????????????? E-mail 
				elseif( !empty( $data['email'] ) && $customer_id = $SocAuth->checkByEmail($data, 1) )
				{
					$SocAuth->setSessionData('socnetauth2_confirmdata', '');
					$SocAuth->setSessionData('socnetauth2_confirmdata_show', '');
					$SocAuth->setSessionData('customer_id', $customer_id);	
					
					if( $IS_DEBUG ) exit( "END-10 ".$CURRENT_URI."<hr>");
				
					header("Location: ".$CURRENT_URI );
				}
				//?????????????? e-mail ?? ???? ???????????????????? 
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