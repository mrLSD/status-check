<?php
/**
 * Redis connection manager
 *
 * @author  Evgeny Ukhanov
 * @version 0.1
 */
class ERedis extends CComponent
{
	/**
	 * Server name for Connection
	 * @var string
	 */
	public $server = "localhost";

	/**
	 * Port for Connection
	 * @var string
	 */
	public $port = 6379;

	protected static $connection;

	/**
	 * Initialize Component
	 */
	public function init()
	{
		// Init Redis connection
		if( !isset(self::$connection) )
		{
			try
			{
				self::$connection = new Redis();
				self::$connection->connect( $this->server, $this->port );
			} catch( MongoConnectionException $e )
			{
				throw new CHttpException(500,'Redis connection failed with message: '.$e->getMessage());
			}
		}
	}

	/**
	 * Get connection
	 * @return mixed
	 */
	public function client()
	{
		return self::$connection;
	}

}