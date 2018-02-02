<?php


namespace Core\ShotCode;


class Code
{
	
	protected $twig;
	protected $tmpl;
	protected $tmplName = 'code.html';

	public function __construct($twig) {
		$this->twig = $twig; 
	}

	public function init() {
		add_shortcode('basic', [$this,'test']);

	}

	public function test() {
		$options = get_option('timelineSetting');

		$template = $this->twig->loadTemplate($this->tmplName);
		if(is_page()) {
			echo $template->render(array(
			    'option' => $options,
			    'resturl' => rest_url()
			));
		}
		

	}

	public function tmpl($tmpl) {
		$this->tmpl = $this->twig->loadTemplate($tmpl);
	}
}
