<?php
/**
 * Comments model
 *
 * @author Evgeny Ukhanov
 * @version Custom CMS  v3
 */
class Cache extends CFormModel
{
	/**
	 * @param $key
	 * @param $func
	 * @return mixed
	 */
	public static function get($key, $func)
	{
		if( ($result=Yii::app()->cache->get( $key )) === FALSE )
		{
			$result = $func();
			Yii::app()->cache->set( $key, json_encode( $result ) );
		}
		return $result;
	}

	/**
	 * Delete cache by key
	 * @param $key
	 */
	public static function del($key)
	{
		Yii::app()->cache->delete( $key );
	}
}
