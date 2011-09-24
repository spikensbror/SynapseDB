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
	
	protected function _FetchAll()
	{
		$return = array();
		while($fetch = mssql_fetch_array($this->_Result))
			$return[] = $fetch;
		return $return;
	}
	
	protected function _Close()
	{
		mssql_close($this->_Connection);
	}
	
	protected function _Free()
	{
		mssql_free_result($this->_Result);
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
