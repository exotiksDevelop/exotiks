<?php
$ROOT = preg_replace("/[^\/\\\]+$/", "", __DIR__);

// Configuration
if (file_exists($ROOT.'config.php')) {
	require_once($ROOT.'config.php');
}

/* start 1410 */
define('VERSION', '2.1.0.0');
define('CURRENT_PAGE', HTTPS_SERVER. basename( getcwd() ).'/upload.php');
/* end 1410 */
// Startup
require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/affiliate.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

$group = getVersionCode($db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' AND (
	`".$group."` LIKE 'russianpost2%' 
) ORDER BY store_id ASC");

foreach ($query->rows as $result) {
	if (!$result['serialized']) {
		$config->set($result['key'], $result['value']);
	} else {
		$config->set($result['key'],  custom_unserialize( json_decode( $result['value'], true)));
	}
}
$registry->set('config', $config);

include_once( DIR_SYSTEM."library/russianpost2/license".getPhpVersion().".php" );
include_once( DIR_SYSTEM."library/russianpost2/russianpost2.php" );
$RP2 = new ClassRussianpost2($registry);

$RP2->uploadPvz(1);

exit('SUCCESS');

// ================

function getPhpVersion()
{
		if( file_exists( DIR_SYSTEM."library/russianpost2/license.php" ) )
			return '';
		$raw = phpversion();
		
		$ar = explode('.', $raw);
		
		if( $ar[0] == 7 )
		{
			if( empty($ar[1]) || $ar[1] == 0 )
				return 70;
			elseif( $ar[1] == 1 )
				return 71;
			else
				return 72;
		}
		elseif($ar[0] == 5 && !empty($ar[1]) && $ar[1] == 3)
		{
			return 53;
		}
		elseif($ar[0] == 5 && !empty($ar[1]) && $ar[1] == 4)
		{
			return 54;
		}
		elseif($ar[0] == 5 && !empty($ar[1]) && $ar[1] == 5)
		{
			return 55;
		}
		else
		{
			return 5;
		}
}

function getVersionCode($db)
{
		$query = $db->query("SELECT * FROM information_schema.COLUMNS
								   WHERE TABLE_NAME = '" . DB_PREFIX . "setting'");
		   
		$column_hash = array();
		
		foreach($query->rows as $row )
		{
			if( $row['TABLE_SCHEMA'] == DB_PREFIX.DB_DATABASE || $row['TABLE_SCHEMA'] == DB_DATABASE )
			{
				$column_hash[ $row['COLUMN_NAME'] ] = 1;
				//echo $row['COLUMN_NAME']."<br>";
			}
		}
		
		
		if( isset($column_hash['group']) ) return 'group';
		else return 'code';
}


function custom_unserialize($s)
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

?>