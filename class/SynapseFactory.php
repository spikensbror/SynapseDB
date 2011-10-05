<?php

/**
 * SynapseDB Database Pooler and Manager.
 */
class SynapseFactory
{
	/**
	 * Holds all currently open database connections.
	 * @var array 
	 */
	private $_Pool = array
	(
		SDB_MYSQL => array(),
		SDB_MSSQL => array()
	);
	
	/**
	 * Cleanup.
	 */
	public function __destruct()
	{
		foreach($this->_Pool as $type => $pool)
			foreach($pool as $database => $object)
				$this->Close($type, $database);
	}
	
	/**
	 * Connects and returns a database object.
	 * @param int $type
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * @param string $database
	 * @return SynapseDatabase 
	 */
	public function &Connect($type, $host, $user, $password, $database)
	{
		if(!isset($this->_Pool[$type][$database]))
		{
			switch($type)
			{
				case SDB_MYSQL:
				{
					$this->_Pool[$type][$database] = new SynapseMyDatabase();
					break;
				}
				
				case SDB_MSSQL:
				{
					if(SDB_OS == SDB_OS_WIN)
						$this->_Pool[$type][$database] = new SynapseMicrosoftDatabase();
					else
						$this->_Pool[$type][$database] = new SynapseMicrosoftTDSDatabase();
					break;
				}
			}
			if(!$this->_Pool[$type][$database]->Establish($host, $user, $password, $database))
				throw new Exception('SynapseDB : Failed to connect to database! MESSAGE('.$this->_Pool[$type][$database]->GetLastError().')');
		}
		return $this->_Pool[$type][$database];
	}
	
	/**
	 * Closes a database connection.
	 * @param int $type
	 * @param string $database
	 * @return void 
	 */
	public function Close($type, $database)
	{
		if(!isset($this->_Pool[$type][$database]))
			return;
		$this->_Pool[$type][$database]->Close();
		unset($this->_Pool[$type][$database]);
	}
	
	// Singleton
	
	/**
	 * Singleton instance.
	 * @var SynapseFactory 
	 */
	private static $_Instance = null;
	
	/**
	 * Get singleton instance.
	 * @return SynapseFactory 
	 */
	public static function GetInstance()
	{
		if(self::$_Instance == null)
			self::$_Instance = new self;
		return self::$_Instance;
	}
	
	/**
	 * Private construct method for singleton.
	 */
	private function __construct()
	{}
	
	/**
	 * Private clone method for singleton.
	 */
	private function __clone()
	{}
}

?>
