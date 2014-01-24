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
	 * List messages for current User
	 */
	public function actionList()
	{
		$model = new MessageForm;
		$users = Users::getUsers($this->user);
		$this->render('list',array(
			'model' => $model,
			'users' => $users,
		));
	}
}