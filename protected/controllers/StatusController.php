<?php
/**
 * Class SiteController
 * Main controller
 *
 * @author	Evgeny Ukhanov
 * @version	0.1
 */
class StatusController extends ProfileController
{
        /**
         * Request filters
         * @return array
         */
        public function filters()
        {
                return array(
                        'ajaxOnly +GetUsersList',
                );
        }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionGetUsersList()
	{
		header('Content-type: application/json');
		$u['users'] = Users::getUsers($this->user);
		echo json_encode( $u );
		die();
	}

}