<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @author	Evgeny Ukhanov
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
                $user = Users::findByEmail( $this->username );
                if( $user === NULL OR $user === FALSE )
                {
                        $this->errorCode = self::ERROR_USERNAME_INVALID;
                }
                elseif($user['password'] != md5($this->password))
                {
                        $this->errorCode = self::ERROR_PASSWORD_INVALID;
                }
                else
                {
                        $this->errorCode = self::ERROR_NONE;
                }
                return !$this->errorCode;
	}
}