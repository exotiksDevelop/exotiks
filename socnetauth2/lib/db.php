<?php

Class DB
{
	protected $DB;
	
	public function __construct()
	{
		$this->DB = mysql_pconnect(DB_HOSTNAME.":".DB_PORT, DB_USERNAME, DB_PASSWORD);
		mysql_select_db(DB_DATABASE, $this->DB);
		mysql_query("SET NAMES utf8");   
	}

	public function query_row($query)
	{
		//echo $query."<hr>";
		
		$result = mysql_query($query);
		#$num = '';
		
		#if( $result )
		$num = mysql_num_rows($result);
    
    
		if(!$num)
		{
			return false;
		}
		
		$row = mysql_fetch_assoc($result);
		mysql_free_result($result);
		
		return $row;
	}
	
	
	public function query_rows($query)
	{
		$result = mysql_query($query);
    
		$num = mysql_num_rows($result);
		$ret = array();
		
		if(!$num)
		{
			return false;
		}
		else
		{
			while($res = mysql_fetch_assoc($result))
			{
				$ret[] = $res;
			}
		}
		
		mysql_free_result($result);
		
		return $ret;
    }
	
	public function query_run($query)
	{
		mysql_query($query);
	}
	
	public function escape($text)
	{
		return mysql_real_escape_string($text);
	}
	
	public function get_last_insert_id()
	{
		return mysql_insert_id();
	}
}
?>