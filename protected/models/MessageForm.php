<?php
/**
 * MessageForm class.
 * Send message Form model
 */
class MessageForm extends CFormModel
{
	public $users;
	public $subject;
	public $body;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('subject, body', 'required'),
			array('subject', 'length', 'max'=>100),
			array('body', 'length', 'max'=>2000),
			array('users', 'safe'),




		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
		);
	}
}