<?php
/**
 * This is the Base model class
 *
 * @author Evgeny Ukhanov
 * @version 0.1
 */
class BaseModel extends CFormModel
{
	public $collection;
	public $cache_name;
	private static $_models=array();

	/**
	 * Init model class
	 * @param $model
	 */
	public static function page($model)
	{
		if( is_null( self::$_models[$model] ) )
			self::$_models[$model] = new $model;
		return self::$_models[$model];
	}

	/**
	 * @return MongoCollection
	 */
	public function model()
	{
		return Yii::app()->dbm->db->{ $this->collection };
	}

	/**
	 * Load model
	 * @param $_id
	 * @return array|null
	 */
	public function loadModelByID($_id)
	{
		$model = $this->model();
		return $model->findOne( array('_id'=>new MongoId($_id)) );
	}

	/**
	 * Load model related with Page
	 * @param $page_id
	 * @return array|null
	 */
	public function  loadModelByPageID($page_id)
	{
		$model = $this->model();
		return $model->findOne( array('page_id'=> $page_id) );
	}

}
