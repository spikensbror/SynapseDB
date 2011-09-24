<?php

/**
 * MySQL Database Object.
 */
class SynapseMyDatabase extends SynapseDatabase
{
	protected function _Connect($host, $user, $password, $database)
	{
		$this->_Connection = @mysql_connect($host, $user, $password, false);
		if(!$this->_Connection || !@mysql_select_db($database, $this->_Connection))
			return false;
		return true;
	}
	
	protected function _Query($query)
	{
		return @mysql_query($query, $this->_Connection);
	}
	
	protected function _FetchAll()
	{
		$return = array();
		while($fetch = mysql_fetch_array($this->_Result))
			$return[] = $fetch;
		return $return;
	}
	
	protected function _Close()
	{
		mysql_close($this->_Connection);
	}
	
	protected function _Free()
	{
		mysql_free_result($this->_Result);
	}
	
	protected function _Sanitize($argument)
	{
		return '\''.mysql_real_escape_string($argument, $this->_Connection).'\'';
	}
	
	public function GetLastError()
	{
		return mysql_error($this->_Connection);
	}
}

?>
