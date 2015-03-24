<?php namespace Slytherin;

/**
 * Base Model Class
 */

class Model {

	public $databaseHandle = NULL;

	public function __construct()
	{
		$credentials = require 'app/config/database.php';

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