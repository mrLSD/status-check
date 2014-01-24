<?php
/**
 * Class MessagesController
 * Messages handlers
 *
 * @author	Evgeny Ukhanov
 * @version	0.1
 */
class MessagesController extends ProfileController
{
	/**
	 * List messages for current User and
	 * send message to custom users
	 */
	public function actionList()
	{
		$model = new MessageForm;
		$users = Users::getUsers($this->user);
		// Get Form data
		if( isset( $_POST['users']) AND $_POST['MessageForm'] )
		{
			// Set message attributes
			$model->attributes = $_POST['MessageForm'];
			// Strip HTML
			$model->subject = strip_tags( $model->subject );
			$model->body = strip_tags( $model->body );
			// Set recipients
			$model->users = $_POST['users'];
			//===========================================================
			// Check is users at Users list
			$finded_count = 0;
			foreach( $users['users'] as $user )
			{
				if( in_array( (string)$user['_id'], $model->users ) )
					$finded_count++;
			}
			if( $finded_count != count( $model->users ) )
			{
				$model->addError("subject","Invalid selected users");
			}
			//===========================================================
			// Validate and send
			elseif( $model->validate() )
			{
				Messages::saveMessage( $this->user, $model->attributes );
				Yii::app()->user->setFlash('sended','');
				$this->refresh();
			}
		}
		$this->render('list',array(
			'model' => $model,
			'users' => $users,
			'messages' => Messages::getMessages( $this->user ),
		));
	}
}