<?php
/**
 * MongoDB connection manager
 *
 * @author  Evgeny Ukhanov
 * @version 0.1
 */
class EMongoDBConnection extends CComponent
{
	/**
	 * Server name for Connection
	 * @var string
	 */
	public $server = "mongodb://localhost:27017";
	/**
	 * Connection options
	 * @var array
	 */
	public $options = array();

	/**
	 * DB name
	 * @var string
	 */
	public $db_name;

	protected static $connection;
	protected static $db;

	/**
	 * Initialize Component
	 */
	public function init()
	{
		// Init MongoDB connection
		if( !isset(self::$connection) )
		{
			try
			{
				self::$connection = new MongoClient( $this->server, $this->options );
				$this->db = $this->db_name;
			} catch( MongoConnectionException $e )
			{
				throw new CHttpException(500,'MongoDB connection failed with message: '.$e->getMessage());
			}
		}
	}

	/**
	 * Set DB for Connection
	 * @param $db_name
	 */
	public function setDB($db_name)
	{
		self::$db = self::$connection->selectDB($db_name);
	}

	/**
	 * Get DB instance
	 * @return mixed
	 */
	public function  getDB()
	{
		return self::$db;
	}

	/**
	 * Check DB instance set to connection
	 * @return bool
	 */
	public function checkDB()
	{
		return isset(self::$db);
	}

}
