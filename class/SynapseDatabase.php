<?php

/**
 * Base synapse object.
 */
abstract class SynapseDatabase
{
	/**
	 * The token to identify tokens in query string.
	 */
	const ARGUMENT_TOKEN = '?';
	
	/**
	 * Connection result/object.
	 * @var object 
	 */
	protected $_Connection = null;
	
	/**
	 * SQL result data.
	 * @var result 
	 */
	protected $_Result = null;
	
	/**
	 * SQL fetch data.
	 * @var array 
	 */
	protected $_Fetch = null;
	
	/**
	 * Fetch iterator position.
	 * @var int 
	 */
	protected $_FetchPosition = 0;
	
	/**
	 * Establish a connection for the object.
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param string $database
	 * @return bool 
	 */
	public function Establish($host, $user, $password, $database)
	{
		return $this->_Connect($host, $user, $password, $database);
	}
	
	/**
	 * Queries a string to the database.
	 * @param string $query
	 * @param array $arguments 
	 */
	public function Query($query, $arguments = array())
	{
		for($i = 0; $i < sizeof($arguments); $i++)
			$arguments[$i] = $this->_Sanitize($arguments[$i]);
		$query = preg_split(
			'/('.preg_quote(SynapseDatabase::ARGUMENT_TOKEN).')/',
			$query,
			null,
			PREG_SPLIT_DELIM_CAPTURE
		);
		$i = 0;
		for($j = 0; $j < sizeof($query); $j++)
		{
			if($query[$j] == SynapseDatabase::ARGUMENT_TOKEN)
			{
				if(!isset($arguments[$i]))
					return false;
				$query[$j] = $arguments[$i];
				$i++;
			}
		}
		if($i != sizeof($arguments))
			return false;
		$query = implode('', $query);
		$result = $this->_Query($query);
		if(!$result && SDB_DEBUG)
			throw new Exception('SynapseDB : Query failed! QUERY('.$query.') MESSAGE('.$this->GetLastError().')');
		$this->_Result = $result;
		return true;
	}
	
	/**
	 * Fetches data in both associative and numerical form from the database.
	 * @param int $type
	 * @return array 
	 */
	public function Fetch($type = SDB_FETCH_BOTH)
	{
		if($this->_Result != null)
		{
			if($this->_Fetch == null)
				$this->_Fetch = $this->_FetchAll();
			if($this->_FetchPosition == sizeof($this->_Fetch))
				$this->_FetchPosition = 0;
			else
			{
				$row = $this->_Fetch[$this->_FetchPosition++];
				foreach($row as $key => $value)
				{
					if((!is_int($key) && $type == SDB_FETCH_NUMERIC)
						|| is_int($key) && $type == SDB_FETCH_ASSOC)
					{
						unset($row[$key]);
						continue;
					}
					if(is_numeric($value))
						$row[$key] = (int)$value;
				}
				return $row;
			}
		}
		return false;
	}
	
	/**
	 * Closes the connection.
	 */
	public function Close()
	{
		$this->_Close();
	}
	
	/**
	 * Frees all result related data.
	 */
	public function Free()
	{
		$this->_FetchPosition = 0;
		if($this->_Fetch != null)
			unset($this->_Fetch);
		if($this->_Result != null)
			$this->_Free();
	}
	
	// Abstracts.
	
	/**
	 * Connects to the server and selects database.
	 */
	abstract protected function _Connect($host, $user, $password, $database);
	
	/**
	 * Queries the database.
	 */
	abstract protected function _Query($query);
	
	/**
	 * Fetches all rows from the current result.
	 */
	abstract protected function _FetchAll();
	
	/**
	 * Closes the database connection.
	 */
	abstract protected function _Close();
	
	/**
	 * Frees the current result.
	 */
	abstract protected function _Free();
	
	/**
	 * Sanitizes a query argument.
	 */
	abstract protected function _Sanitize($argument);
	
	/**
	 * Gets the last error returned from the server.
	 */
	abstract public function GetLastError();
}

?>
