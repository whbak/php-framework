<?php

class UserService
{
	public $twig;
	public function __construct($twig)
	{
		$this->twig = $twig;
	}

	public function validateLoggedIn()
	{
		/* redbeans */
		$username = $_SESSION['loggedInUser'];
		$sessiontoken = $_SESSION[$username];
		$sessions = R::getrow(
			"SELECT token FROM sessions WHERE username = :username AND token = :token",
			[':username' => $username, ':token' => $sessiontoken]
		);
		if (!empty($sessiontoken) && !empty($sessions)) {
			if ($sessiontoken == $sessions['token']) {
				return true;
			} else {
				return false;
			}
		}
	}
}