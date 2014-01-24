<?php
/**
 * This is the model class for Messages
 *
 * @author Evgeny Ukhanov
 * @version 0.1
 */
class Messages extends CFormModel
{
	protected static $collection = 'messages';

	/**
	 * @return MongoCollection
	 */
	public static function model()
	{
		return Yii::app()->dbm->db->{ self::$collection };
	}

	/**
	 * Save message and set New message increment
	 * @param array $user
	 * @param array $attributes
	 * @return bool
	 */
	public static function saveMessage( $user, $attributes )
	{
		$model = self::model();
		// Set time creation
		$attributes['from'] = array(
			'id' => (string)$user['_id'],
			'name' => $user['name'],
			'email' => $user['email'],
		);
		$attributes['created'] = time();
		// Set New message count to Cache
		foreach( $attributes['users'] as $user_id )
		{
			Yii::app()->redis->client()->incr('message:new:'.$user_id);
		}
		$model->save( $attributes );
		return true;
	}

	/**
	 * Get messages for current user
	 * @param array $user
	 * @return array
	 */
	public static function getMessages( $user )
	{
		$user_id = (string)$user['_id'];
		$model = self::model();
		$messages = $model->find(array(
			"users" => array(
				'$in' => array( $user_id ),
			),
		))->sort( array("created"=>-1));
		// Set new messages as readed
		Yii::app()->redis->client()->set('message:new:'.$user_id, 0);
		return iterator_to_array($messages, false);

	}
}