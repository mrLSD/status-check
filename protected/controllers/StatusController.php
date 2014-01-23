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
                        'ajaxOnly +GetUsersList, SetUserStatusMessage',
                );
        }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * Get users list and their statuses
	 */
	public function actionGetUsersList()
	{
		// Set current user status "online"
		Users::setUserOnline( $this->user );
		// Set User HEAD type and render response
		header('Content-type: application/json');
		// Get User and statuses and return JSON
		echo json_encode( Users::getUsers($this->user) );
		die();
	}

	/**
	 * Set user Status Message
	 */
	public function actionSetUserStatusMessage()
	{
		if( !isset( $_POST['message']) )
		{
			$response_message = 'failed';
		} else {
			// Set current user status "online"
			if( Users::setUserStatusMessage( $this->user, $_POST['message'] ) )
				$response_message = 'ok';
			else
				$response_message = 'failed';
		}
		die( json_encode(array(
			'response' => $response_message,
		)) );
	}


}