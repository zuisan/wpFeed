<?php

namespace Core;

class Session
{
	function __construct()
	{
		if (!session_id()) {
			session_start();
			session_regenerate_id(true);
		}
	}	
	public function init() {
	}
	public function setSession($key,$val) {
		$_SESSION[$key] = $val;
	}
	public function getSession($key) {
		return $_SESSION[$key];
	}
	public function clearSession($key) {
		unset($_SESSION[$key]);
	}
	public function clearSessionAll() {
		$_SESSION = array();
		setcookie(session_name(), '', time()-1, '/');
		session_destroy();
	}		
	public function getSessionAll() {
		return $_SESSION;
	}
}

