<?php
/**
 * This is the model class for Users
 *
 * The followings are the available columns in form 'users':
 *
 * @author Evgeny Ukhanov
 * @version 0.1
 */
class Users extends CFormModel
{
	public $_id;
	public $name;
	public $email;
	public $password;
	public $status_message;

	protected static $collection = 'users';

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, password', 'required'),
                        array('status_message', 'length','max'=>20),
			array('_id', 'safe'),
		);
	}

	/**
	 * @return MongoCollection
	 */
	public static function model()
	{
		return Yii::app()->dbm->db->{ self::$collection };
	}

	/**
	 * Check is User at DB and get Their data
	 * @return array|bool
	 */
	public static function identifyUser()
	{
		if( Yii::app()->user->name == 'Guest')
			return false;
		$user =  self::findByEmail( Yii::app()->user->name );
		if( $user === FALSE )
			Yii::app()->user->logout();
		return $user;
	}

	/**
	 * Find user by Email
	 * @param string $email
	 * @return array|bool
	 */
	public static function findByEmail( $email )
	{
		$result = self::model()->findOne( array(
			'email' => $email,
		) );
		if( !is_null($result) )
			return $result;
		else
			return false;
	}

	/**
	 * Save User Model
	 * @param object $class
	 * @return array|bool
	 */
	public static function save($class)
	{
		// Get collection
		$model = self::model();
		$user_model = new self;
		// Set attribute to model
		$user_model->attributes = $class->attributes;
		$user_model->_id = new MongoId();
		if( !$user_model->validate() )
			return false;
		// Change Password to MD5
		$user_model->password = md5( $user_model->password );
		// Save record
		$model->save( $user_model->attributes );
		return true;
	}

	/**
	 * Load model by ID
	 *
	 * @param string $id
	 * @return array|null
	 * @throws CHttpException
	 */
	public static function loadModel($id)
	{
		$model = self::model();
		$result = $model->findOne( array('_id'=>new MongoId($id)) );
		if( is_null($result) )
			throw new CHttpException(404,"Requested page not found.");
		return $result;
	}

	/**
	 * Get all system Users exlude current
	 * Get online data from cache
	 * @param array $currentuser Current User
	 * @return CArrayDataProvider
	 */
	public static function getUsers($currentuser)
	{
		// Get users statuses
		$keys = Yii::app()->redis->client()->keys('users:online:*');
		$users_online = Yii::app()->redis->client()->mget( $keys );
		if( !$users_online )
		{
			$users_online_count = 0;
			$users_online = array();
		} else {
			$users_online_count = count( $users_online ) - 1;
		}

		// Get user list
		$model = self::model();
		$users = $model->find(array(
			"_id" => array(
				'$ne' => $currentuser['_id'],
			)
		));
		$users = iterator_to_array( $users, false );
		// Manually set  special users parameters
		foreach( $users as $key=>$user )
		{
			if( in_array( (string)$users[ $key ]['_id'], $users_online ) )
			{
				$users[ $key ]['online'] = "online";
			} else {
				$users[ $key ]['online'] = "offline";
				$users[ $key ]['status_message'] = "";
			}
		}
		return array(
			'users' => $users,
			'online' => $users_online_count ,
		);
	}

	/**
	 * Set user online status to cache
	 * @param array $user Current User data
	 */
	public static function setUserOnline( $user )
	{
		$user_id = (string)$user['_id'];
		// Set cached data with 10 sec Expirarion
		Yii::app()->redis->client()->set('users:online:'.$user_id, $user_id, 10);
	}

	/**
	 * Set user status message
	 * @param array $user Current User data
	 * @return bool Success result
	 */
	public static function setUserStatusMessage( $user, $message )
	{
		// Strip HTML tags
		$message = strip_tags( $message );
		// Init form-model data
		$user_model = new self;
		$user_model->attributes = $user;
		$user_model->status_message = $message;
		// Validate and save
		if( $user_model->validate() )
		{
			self::model()->save( $user_model->attributes );
			return true;
		} else {
			return false;
		}


	}
}