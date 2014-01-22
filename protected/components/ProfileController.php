<?php
/**
 * Profilec base controller.
 * All action for logged-in users are parented from it.
 *
 * @author 	Evgeny Ukganov
 * @version	0.1
 */
class ProfileController extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

	/**
	 * @var string Logged in user ant their db-data
	 */
	public $user;

        /**
         * @return array action filters
         */
        public function filters()
        {
                return array(
			'accessControl', // perform access control for CRUD operations
		);
        }

        /**
         * Specifies the access control rules.
         * This method is used by the 'accessControl' filter.
         * @return array access control rules
         */
        public function accessRules()
        {
                // Check User Status for Accessing
                if( !!$this->user )
                {
                        return array(
                                array('allow',
                                        'users'=>array(Yii::app()->user->name),
                                ),
                                array('deny',
                                        'users'=>array('*'),
                                ),
                        );
                } else
                {
                        return array(
                                array('deny',
                                        'users'=>array('*'),
                                ),
                        );
                }
        }

	/**
	 * Init base controller
	 */
	public function init()
	{
		$this->user = Users::identifyUser();
		if( !$this->user )
			$this->redirect('/user/login');
	}

}