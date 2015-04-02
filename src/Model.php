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
	 * accessing the database
	 */
	public function __construct()
	{
		$credentials = Config::load('app/config/database.php');

		$this->databaseHandle = new \PDO(
			$credentials['driver'] .
			':host=' . $credentials['hostname'] .
			';dbname=' . $credentials['database'],
			$credentials['username'],
			$credentials['password']
		);

		$this->databaseHandle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	}

}