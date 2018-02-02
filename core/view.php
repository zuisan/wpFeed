<?php

namespace Core;

require_once WP_PLUGIN_DIR.'/feed/lib/Twig/Autoloader.php';

class View
{

	private $view;
	public function __construct() {

	}
	public static function init() {
		\Twig_Autoloader::register();
		$loader = new \Twig_Loader_Filesystem(WP_PLUGIN_DIR."/feed/view");
		$Twig = new \Twig_Environment($loader,array('debug' => true));
		$Twig->addExtension(new \Twig_Extension_Debug());
		return $Twig;
	}
}
