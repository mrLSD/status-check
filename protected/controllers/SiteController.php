<?php
/**
 * Class SiteController
 * Site controller. Handler Errors action
 *
 * @author	Evgeny Ukhanov
 * @version	0.1
 */
class SiteController extends BaseController
{
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}