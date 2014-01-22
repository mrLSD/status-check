<?php
/**
 * DB Pages managment for Front-end
 *
 * @author Evgeny Ukhanov
 * @version  0.1
 */
class Pages extends BaseModel
{
	public $collection = 'pages';
	public $cache_name = 'front.pages';

	/**
	 * Models related with Pages model
	 * @var array
	 */
	public static $relation_models = array(
		'teachers', 'levels', 'durations', 'styles', 'specificuse',
	);

	/**
	 * Init Model as page
	 * @param string $model
	 * @return mixed
	 */
	public static function page($model=__CLASS__)
	{
		return parent::page($model);
	}

	/**
	 * Find some URL at SEO via SQL LIKE request
	 * We find URL from parent_id visibility and != current id (it's for updates)
	 *
	 * @param string $url
	 * @param string $status
	 * @return MongoCursor
	 */
	public function FindPageByURL($url, $status=array('visible'))
	{
		// APC get
		/*
		$_t = microtime(true);
		$res = apc_fetch( $url, $success );
		$ag = microtime(true) - $_t;
		vd::d(array(
			'apc_get'=> $success,
			'result' => $res,
			'APC_get_time' => $ag,
		));*/
		// Redis get
		/*
		$redis = new Redis();
		$redis->connect( 'localhost', 6379 );
		$_t = microtime(true);
		$res = $redis->get( $url );
		$rg1 = microtime(true) - $_t;
		$res = json_decode( $res, true );
		$rg = microtime(true) - $_t;
		vd::d(array(
			//'result' => $res,
			'Redis_get_nojson' => $rg1,
			'Redis_get_time' => $rg,
			'APCvsRedis' => $rg/$ag,
			'APCvsRedisNoJSON' => $rg1/$ag,
		));*/

		// Memcached set
/*		$mc = new Memcached();
		$mc->addServer('localhost', 11211);
		$_t = microtime(true);
		$res = $mc->get($url);
		$mcg = microtime(true) - $_t;
		vd::d(array(
			//'result' => $res,
			'MC_get_time' => $mcg,
			'APCvsMemcached' => $mcg/$ag,
			'MemcachedVSRedis' => $rg/$mcg,
		));*/

		/*
		$_t = microtime(true);
		*/
		$call = $this;
		$res = Cache::get($url ,function() use ($url, $status, $call)
		{
			// Mongo get
			$model = $call->model();
			return $model->findOne( array(
				'seo.full_url' => $url,
				'status' => array('$in'=>$status),
			) );
		} );
		/*
		$mg = microtime(true) - $_t;
		*/
		// APC set
		/*
		$_t = microtime(true);
		apc_store( $url, $res);
		$as = microtime(true) - $_t;
		*/

		// Redis Set
		/*
		$_t = microtime(true);
		$_res=json_encode($res);
		$_res=$redis->set($url,$_res);
		$rs = microtime(true) - $_t;
		*/
		// Memcached Set
		/*
		$_t = microtime(true);
		$mc->set( $url, $model );
		$mcs = microtime(true) - $_t;
		*/
		/*
		vd::d(array(
			"Mongo get: "=>$mg,
			"MongoVSRedis" => $mg/$rg,
			"MongoVSAPC" => $mg/$ag,
			//"MongoVSMemcached" => $mg/$mcg,
		));*/

		//apc_clear_cache();
		//apc_clear_cache('user');
		//apc_clear_cache('opcode');
		return $res;
	}

	/**
	 * Get Classes related with
	 * @param array $category
	 * @return array
	 */
	public function getClassesList($criteria=array())
	{
		$cahe_key = serialize( $criteria );
		$call = $this;
		return Cache::get( $cahe_key, function() use ($criteria, $call)
		{
			$model = $call->model();
			$cursor = $model->find( array_merge( $criteria, array(
				'status' => array('$nin'=>array('unsaved','hidden') ),
			) ) )->sort( array('tabindex'=>1) );
			$result = iterator_to_array( $cursor );
			if( $cursor->count() == 0 )
				$result = array();
			return $result;
		});
	}

	/**
	 * Get Blog Categories
	 * @return array
	 */
	public function getBlogPostsByTypeCategory( $category )
	{
		$cahe_key = "blog.post.".$category;
		$call = $this;
		return Cache::get( $cahe_key, function() use ($category, $call)
		{
			$model = $call->model();
			$cursor = $model->find( array(
				'tag.category' => $category,
				'category' => 'blog',
				'type' => 'post',
				'status' => array('$nin'=>array('unsaved','hidden') ),
			) )->sort( array('tabindex'=>1) );
			$result = iterator_to_array( $cursor );
			if( $cursor->count() == 0 )
				$result = array();
			return $result;
		});
	}

	/**
	 * Get Blog Categories
	 * @return array
	 */
	public function getBlogCategories()
	{
		$cahe_key = "blog.categories";
		$call = $this;
		return Cache::get( $cahe_key, function() use ($call)
		{
			$model = $call ->model();
			$cursor = $model->find( array(
				'tag.category' => array('$ne'=>''),
				'category' => 'blog',
				'type' => 'post',
				'status' => array('$nin'=>array('unsaved','hidden') ),
			), array(
				'tag.category'=>1,
				'seo.full_url'=>1,
			) )->sort( array('tag.category'=>1) );
			$result = iterator_to_array( $cursor );
			if( $cursor->count() == 0 )
				$result = array();
			return $result;
		});
	}

	/**
	 * Get Menu items by category
	 * @param string $category
	 * @return array
	 */
	public function getBreadCrumbs($parent_id, $menu=array())
	{
		$cahe_key = "BreadCrumbs.".(string)$parent_id;
		$call = $this;
		return Cache::get( $cahe_key, function() use ($parent_id, $menu, $call)
		{
			$model = $call->model();
			$parent_model = $model->findOne( array(
				'_id' => $parent_id,
				'status' => array('$nin'=>array('unsaved','hidden') ),
			), array(
				'title'=>1,
				'seo.full_url'=>1,
				'parent_id'=>1,
			) );
			if( !is_null( $parent_model ) )
			{
				array_unshift( $menu, $parent_model );
				// Get parent Items
				if( !is_null( $parent_model['parent_id'] ) )
				{
					$menu = $this->getBreadCrumbs( $parent_model['parent_id'], $menu );
				}
			}
			return $menu;
		});
	}

	/**
	 * Get Menu items by category
	 * @param string $category
	 * @return array
	 */
	public function getMenuItemsByCategory($category, $parent_id=null)
	{
		$criteria = array(
				'parent_id' => $parent_id,
				'category' => $category,
				'status' => array('$nin'=>array('unsaved','hidden') ),
			);
		$cache_key = serialize( $criteria );
		$call = $this;
		return Cache::get( $cache_key, function() use ($category, $parent_id, $criteria, $call)
		{
			$model = $call->model();
			$cursor = $model->find( $criteria, array(
				'title'=>1, 'seo'=>1,
			) )->sort( array('tabindex'=>1) );
			$result = iterator_to_array( $cursor );
			if( $cursor->count() == 0 )
				$result = array();
			return $result;
		});
	}

	/**
	 * Get count of video-pages related with specific SpecificUse and Series
	 * @param $page_id_of_specificuse
	 * @param $series
	 * @return int
	 */
	public function getSpecificUseVideoCountWithSeries($page_id_of_specificuse, $series)
	{
		$criteria = array(
			'category' => 'video',
			'specificuse.page_id' => (string)$page_id_of_specificuse,
			'series' => $series,
			//'status' => array('$nin'=>array('unsaved','hidden') ),
		);
		$cache_key = serialize( $criteria );
		$call = $this;
		return Cache::get( $cache_key, function() use ($criteria, $call)
		{
			$model = $call->model();
			return $model->find( $criteria, array(
				'_id'=>1,
			) )->count();
		});
	}

	/**
	 * Get pages of Specific category and Type (if set)
	 *
	 * @param     $parent_id
	 * @param string $type
	 * @return array
	 */
	public function getPagesListByParentID($parent_id=NULL,$type=NULL)
	{
		if( is_null( $type ) )
		{
			$criteria = array(
				'parent_id' => $parent_id,
				'status' => array('$nin'=>array('unsaved','hidden') ),
			) ;
		} else
		{
			$criteria = array(
				'parent_id' => $parent_id,
				'type' => $type,
				'status' => array('$nin'=>array('unsaved','hidden') ),
			) ;
		}
		$cache_key = serialize( $criteria );
		$call = $this;
		return Cache::get( $cache_key, function() use ($criteria, $parent_id, $call)
		{
			$model = $call->model();
			$cursor =  $model->find( $criteria )->sort( array('tabindex'=>1) );
			$result = iterator_to_array( $cursor );
			if( $cursor->count() == 0 AND is_null( $parent_id ) )
				$result = array();
			return $result;
		});
	}

	/**
	 * Get Page - main category
	 * @param $category
	 * @return array|null
	 */
	public function getMainCategory($category)
	{
		$criteria = array(
			'category' => $category,
			'parent_id' => NULL,
			'status' => array('$nin'=>array('unsaved','hidden') ),
		) ;
		$cache_key = "maincategory.".serialize( $criteria );
		$call = $this;
		return Cache::get( $cache_key, function() use ($criteria, $call)
		{
			$model = $call->model();
			return $model->findOne( $criteria );
		});
	}

	/**
	 * Get Video Classes by ID's of classes
	 * @param array $ids
	 * @return array
	 */
	public function getVideosByIDs( $ids=array() )
	{
		$cache_key = serialize($ids);
		$call = $this;
		return Cache::get( $cache_key, function() use ($ids, $call)
		{
			$model = $call->model();
			if( is_null($ids) OR !is_array($ids) )
				$ids = array();
			$_ids = array();
			foreach( $ids as $_id=>$value )
			{
				$_ids[] = new MongoId( $_id );
			}
			$criteria = array(
				'_id' => array('$in' => $_ids),
				'status' => array('$nin'=>array('unsaved','hidden') ),
			);
			$cursor =  $model->find( $criteria )->sort( array('tabindex'=>1) );
			$result = iterator_to_array( $cursor );
			if( $cursor->count() == 0 )
				$result = array();
			return $result;
		});
	}

	/**
	 * Load model by ID
	 *
	 * @param string $id
	 * @return array|null
	 * @throws CHttpException
	 */
	public static function loadModel($id, $check_exist=false)
	{
		$cache_key = "page.".(string)$id;
		$call = __CLASS__;
		return Cache::get( $cache_key, function() use ($id, $check_exist, $call)
		{
			$model = $call::page()->model();
			$result = $model->findOne( array('_id'=>new MongoId($id)) );
			if( is_null($result) AND $check_exist )
				throw new CHttpException(404,"Requested page not found.");
			return $result;
		});
	}
}
