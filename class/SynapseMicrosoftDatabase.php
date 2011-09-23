<?php

/**
 * Microsoft SQL Driver Database Object.
 */
class SynapseMicrosoftDatabase extends SynapseDatabase
{
	protected function _Connect($host, $user, $password, $database)
	{
		$this->_Connection = @sqlsrv_connect($host, array('UID' => $user, 'PWD' => $password, 'Database' => $database));
		if(!$this->_Connection)
			return false;
		return true;
	}
	
	protected function _Query($query)
	{
		return @sqlsrv_query($this->_Connection, $query);
	}
	
	protected function _Fetch($type, $query)
	{
		$return = array();
		switch($type)
		{
			case SDB_FETCH_BOTH:
			{
				while($fetch = sqlsrv_fetch_array($query))
					$return[] = $fetch;
				break;
			}
			case SDB_FETCH_ASSOC:
			{
				while($fetch = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC))
					$return[] = $fetch;
				break;
			}
			case SDB_FETCH_NUMERIC:
			{
				while($fetch = sqlsrv_fetch_array($query, SQLSRV_FETCH_NUMERIC))
					$return[] = $fetch;
				break;
			}
		}
		return $return;
	}
	
	protected function _Close()
	{
		sqlsrv_close($this->_Connection);
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
		return print_r(sqlsrv_errors(), true);
	}
}

?>
