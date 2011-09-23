<?php

/**
 * Microsoft SQL FreeTDS Database Object.
 */
class SynapseMicrosoftTDSDatabase extends SynapseDatabase
{
	protected function _Connect($host, $user, $password, $database)
	{
		$this->_Connection = @mssql_connect($host, $user, $password, false);
		if(!$this->_Connection || !@mssql_select_db($database, $this->_Connection))
			return false;
		return true;
	}
	
	protected function _Query($query)
	{
		return @mssql_query($query, $this->_Connection);
	}
	
	protected function _Fetch($type, $query)
	{
		$return = array();
		switch($type)
		{
			case SDB_FETCH_BOTH:
			{
				while($fetch = mssql_fetch_array($query))
					$return[] = $fetch;
				break;
			}
			case SDB_FETCH_ASSOC:
			{
				while($fetch = mssql_fetch_assoc($query))
					$return[] = $fetch;
				break;
			}
			case SDB_FETCH_NUMERIC:
			{
				while($fetch = mssql_fetch_row($query))
					$return[] = $fetch;
				break;
			}
		}
		return $return;
	}
	
	protected function _Close()
	{
		mssql_close($this->_Connection);
	}
	
	protected function _Sanitize($argument)
	{
		if(is_numeric($argument))
			return $argument;
		$unpacked = unpack('H*hex', $argument);
		return '0x'.$unpacked['hex'];
	}
	
	public function GetLastError()
	{
		return mssql_get_last_message();
	}
}

?>
