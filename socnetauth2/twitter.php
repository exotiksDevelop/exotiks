<?php
require_once('../config.php');
require_once('lib/db.php');
require_once('lib/socnetauth2.php');
require_once('lib/twitter/twitteroauth.php');
session_start();

$SocAuth = new SocAuth();
if( ( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ) || 
			!empty($_SERVER['HTTPS']) )
	$protokol = 'https://';
else
	$protokol = 'http://';
$SocAuth->setGroupName();

/* start r3 */
$IS_DEBUG = $SocAuth->get_config_param('socnetauth2_twitter_debug');

if( !$SocAuth->get_config_param('socnetauth2_twitter_status') )
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

$socnetauth2_shop_folder = $SocAuth->get_config_param('socnetauth2_shop_folder');
if( $socnetauth2_shop_folder ) $socnetauth2_shop_folder = '/'.$socnetauth2_shop_folder;
	

$CONSUMER_KEY = $SocAuth->get_config_param('socnetauth2_twitter_consumer_key');
$CONSUMER_SECRET = $SocAuth->get_config_param('socnetauth2_twitter_consumer_secret');
$CALLBACK_URL = $protokol.$_SERVER['HTTP_HOST'].$socnetauth2_shop_folder.'/socnetauth2/twitter.php';

if( $IS_DEBUG  )
{
	echo $CALLBACK_URL."<hr>";
}

/** Здесь необходимо подставить значения своего приложения */  
define("CONSUMER_KEY", $CONSUMER_KEY);
define("CONSUMER_SECRET", $CONSUMER_SECRET);
define("CALLBACK_URL", $CALLBACK_URL);



if( !empty($_GET['error']) && !empty( $_COOKIE['tw_state'] ) &&
	$recordData = $SocAuth->getRecord( $_COOKIE['tw_state'] ) )
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

if( empty($_REQUEST['oauth_token']) )
{
	if($IS_DEBUG)
	{
		echo "M2<hr>";
	}
	
    $twitteroauth = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);    
    $request_token = $twitteroauth->getRequestToken($CALLBACK_URL);
    
    $_SESSION['oauth_token'] = $request_token['oauth_token'];  
    $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];  
    
	$STATE = md5($request_token['oauth_token'].$request_token['oauth_token_secret']);
	setcookie("tw_state", $STATE);
	
	$SocAuth->setRecord($STATE, $_SERVER['HTTP_REFERER']);
	
    if($twitteroauth->http_code==200){  
        $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);  


		if($IS_DEBUG)
		{
			if( strstr($url, "?") )
			{
				$url .= '&error=2';
			}
			else
			{
				$url .= '?error=2';
			}
		}
		
        header('Location: '. $url); 
    } else {          
        die('Something wrong happened.');  
    }  

}
elseif( $recordData = $SocAuth->getRecord( md5($_SESSION['oauth_token'].$_SESSION['oauth_token_secret']) ) ) 
{
	if($IS_DEBUG)
	{
		echo "M3<hr>";
	}
	
	$twitteroauth = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $access_token = $twitteroauth->getAccessToken($_GET['oauth_verifier']);      
    $_SESSION['access_token'] = $access_token; 
    $userdata = $twitteroauth->get('account/verify_credentials');
	
	$CURRENT_URI = $recordData['redirect'];
	$CURRENT_URI = str_replace("&socnetauth2close=1", "", $CURRENT_URI);
	$CURRENT_URI = str_replace("?socnetauth2close=1", "", $CURRENT_URI);
	
	if($IS_DEBUG)
	{
		echo "M4: ".$CURRENT_URI."<hr>";
	}
	
	
	$provider = 'twitter';
	
	$ar1 = explode(" ", $userdata->name);
	$first_name = $ar1[0];
	$last_name = $ar1[1];
	
	$arr = array(
			'identity' => $userdata->id,
			'firstname' => $ar1[0],
			'lastname'  => $ar1[1],
			'email'     => '',
			'telephone'	=> '',
			'link'		=> "https://twitter.com/".$userdata->screen_name
		);
	
	$data = array(
			'identity'  => $arr['identity'],
			'link' 		=> $arr['link'],
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

?>