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
	
	protected function _Fetch($type, $query)
	{
		$return = array();
		switch($type)
		{
			case SDB_FETCH_BOTH:
			{
				while($fetch = mysql_fetch_array($query))
					$return[] = $fetch;
				break;
			}
			case SDB_FETCH_ASSOC:
			{
				while($fetch = mysql_fetch_assoc($query))
					$return[] = $fetch;
				break;
			}
			case SDB_FETCH_NUMERIC:
			{
				while($fetch = mysql_fetch_row($query))
					$return[] = $fetch;
				break;
			}
		}
		return $return;
	}
	
	protected function _Close()
	{
		mysql_close($this->_Connection);
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
