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
					throw new Exception('SynapseDB : Too few arguments supplied!');
				$query[$j] = $arguments[$i];
				$i++;
			}
		}
		if($i != sizeof($arguments))
			throw new Exception('SynapseDB : Too many arguments supplied!');
		$query = implode('', $query);
		$result = $this->_Query($query);
		if(!$result)
			throw new Exception('SynapseDB : Query failed! QUERY('.$query.') MESSAGE('.$this->GetLastError().')');
		return $result;
	}
	
	/**
	 * Fetches data in both associative and numerical form from the database.
	 * @param string $query
	 * @param array $arguments
	 * @return array 
	 */
	public function Fetch($query, $arguments = array())
	{
		return $this->_Fetch(SDB_FETCH_BOTH, $this->Query($query, $arguments));
	}
	
	/**
	 * Fetches data in an associative form from the database.
	 * @param string $query
	 * @param array $arguments
	 * @return array 
	 */
	public function FetchAssoc($query, $arguments = array())
	{
		return $this->_Fetch(SDB_FETCH_ASSOC, $this->Query($query, $arguments));
	}
	
	/**
	 * Fetches data in a numerical from the database.
	 * @param string $query
	 * @param array $arguments
	 * @return array 
	 */
	public function FetchNumeric($query, $arguments = array())
	{
		return $this->_Fetch(SDB_FETCH_NUMERIC, $this->Query($query, $arguments));
	}
	
	/**
	 * Closes the connection.
	 */
	public function Close()
	{
		$this->_Close();
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
	 * Fetches results from the database.
	 */
	abstract protected function _Fetch($type, $query);
	
	/**
	 * Closes the database connection.
	 */
	abstract protected function _Close();
	
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
