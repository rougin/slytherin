<?php namespace Slytherin;

use Noodlehaus\Config;

/**
 * Base Model Class
 *
 * @package Slytherin
 */
class Model {

	public $databaseHandle = NULL;

	/**
	 * Load the database handle to be used for
	 * accessing the specified database
	 * 
	 * @param string $databaseName
	 */
	public function __construct($databaseName = 'default')
	{
		$credentials = Config::load('app/config/databases.php');
		$credential = $credentials->get($databaseName);

		$this->databaseHandle = new \PDO(
			$credential['driver'] .
			':host=' . $credential['hostname'] .
			';dbname=' . $credential['database'],
			$credential['username'],
			$credential['password']
		);

		$this->databaseHandle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

}