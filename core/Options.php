<?php

namespace Core;

class Options
{

	
	function __construct()
	{

	}	
	public static function getCurrentUrl() {
		return (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	}

	public static function createPermalink($post) {
		foreach ($post as $key => $value) {
			$value->permalink = get_permalink( $value->ID );
		}		
	}

	public function getNonce($name) {
		return wp_create_nonce( $name );

	}

	public function checkPassword($password) {
		$user = $this->getUser();
		$checked = wp_check_password( $password,$user->user_pass);
		return $checked;
	}

	public function getUser() {
		$user = wp_get_current_user();
		return $user;
	}

	public function getURL() {
		$http = is_ssl() ? 'https' : 'http' . '://';
		$url = $http . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		return $url;
	}

	public function is_post() {
		return $_SERVER["REQUEST_METHOD"] === 'POST';
	}
}

