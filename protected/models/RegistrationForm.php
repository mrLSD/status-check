<?php
/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user form data.
 * @author	Evgeny Ukhanov
 * @version	0.1
 */
class RegistrationForm extends CFormModel
{
	public $name;
	public $email;
	public $password;

        protected static $collection = 'users';

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('name, email, password', 'required'),
                        array('name, email, password', 'length', 'max'=>255),
			array('name', 'length', 'min'=>3),
			array('password', 'length', 'min'=>6),
			// email has to be a valid email address
			array('email', 'email'),
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
			'name'=>'User Name',
			'email'=>'E-mail',
		);
	}

        /**
         * @return MongoCollection
         */
        public static function model()
        {
                return Yii::app()->dbm->db->{ self::$collection };
        }

        /**
         * Validate model (add validator)
         * @return bool
         */
        public function validate($attributes = NULL, $clearErrors = true)
        {
                $user = Users::findByEmail( $this->email );
                $result = parent::validate();
                if( $user !== FALSE )
                {
                        $result = false;
                }
                return $result;
        }


}