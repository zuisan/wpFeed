<?php


namespace Core\ShortCode;


class ShortCode
{
	
	protected $View;
	protected $tmplName;
	protected $setting;

	public function __construct() {
		$this->Setting();
	}
	public function Setting() {
		$this->setting = [
			'basicFeed' => 'code.html'
		];
	}
	public function setViews($twig) {
		$this->View = $twig;
	}

	public function init() {
		add_shortcode('basicFeed', [$this,'addFeedShortCode']);
		$headers = getallheaders();

/*
if ($headers['Content-Type'] !== 'application/json') { 
    echo "ajax からのリクエストです";
}


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest'){
echo "ajax からのリクエストです";
}
*/
	}

	public function addFeedShortCode() {
		$options = get_option('timelineSetting');
		$template = $this->getViewTemplate($this->setting['basicFeed']);//loadTemplate($this->setting['basicFeed']);
		if(is_page()) {
			echo $template->render([
			    'option' => $options,
			    'resturl' => rest_url()
			]);
		}
	}

	public function CreateShortCode() {
		//add_shortcode('basicFeed', [$this,'addFeedShortCode']);
	}
	public function getViewTemplate($template) {
		return $this->View->loadTemplate($template);
	}
}
