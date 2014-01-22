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

	protected static $collection = 'users';

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, password', 'required'),
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
	 * @param array $currentuser Current User
	 * @return CArrayDataProvider
	 */
	public static function getUsers($currentuser)
	{
		$model = self::model();
		$result = $model->find(array(
			"_id" => array(
				'$ne' => $currentuser['_id'],
			)
		));
		return iterator_to_array( $result, false );
	}
}