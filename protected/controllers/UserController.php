<?php
/**
 * Class UserControler
 * Manipulations with Users data and sessions. Like:
 * - login/logout
 * - user registration
 *
 * @author	Evgeny Ukhanov
 * @version 	0.1
 */
class UserController extends BaseController
{
	/**
	 * Registration Form and Saving new User data
	 */
	public function actionRegistration()
	{
		$model = new RegistrationForm();
		if(isset($_POST['RegistrationForm']))
		{
			$model->attributes=$_POST['RegistrationForm'];
			if( $model->validate() )
			{
				if( (bool)Users::save( $model ) !== TRUE )
				{
                                        $model->addError("email", "Failed to save this E-mail!");
				} else {
					Yii::app()->user->setFlash('registration','');
					$this->refresh();
				}
			} else {
				$model->addError("email", "Not unique e-mail");
			}
		}
		$this->render('registration',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->homeUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}