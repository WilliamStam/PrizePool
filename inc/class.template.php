<?php
/*
 * Date: 2011/06/27
 * Time: 4:33 PM
 */
Twig_Autoloader::register();
class template {
	private $config = array(), $vars = array();

	function __construct($template, $folder = "", $strictfolder = false) {
		$this->f3 = Base::instance();
		$this->config['cache_dir'] = $this->f3->get('TEMP');

		$this->vars['folder'] = $folder;
		$this->config['strictfolder'] = $strictfolder;

		$this->template = $template;

		$this->timer = new \timer();




	}
	function __destruct(){
		$page = $this->template;
		//test_array($page);

		$this->timer->stop("Template",  $page);
	}

	public function __get($name) {
		return $this->vars[$name];
	}

	public function __set($name, $value) {
		$this->vars[$name] = $value;
	}
	private function default_vars(){

		$uri = $_SERVER['REQUEST_URI'];
		//$url = clean_url($_SERVER['REQUEST_URI']);
		$get = $_SERVER['QUERY_STRING'];

		$url = clean_url("http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		if (isset($_GET['return'])) {
			$return = $_GET['return'];
		} else {
			$return = $this->f3->get("return_here");
		}
		$returnurl = "";
		if ($return) {
			$returnurl = $return;
			$returnurl = @urldecode($returnurl);
			$returnurl = @base64_decode($returnurl);
		}


		$this->vars['_url'] = array(
			"uri" => $uri,
			"url" =>$url,
			"url_encode" => base64_encode($url),
			"querystring" => $get,
			"get"=> $_GET,
			"return"=> $return,
			"return_url"=> $returnurl,

		);





		$this->vars['_user'] = $this->f3->get('user');
		$this->vars['_isAjax'] = is_ajax();

	}


	public function load() {
		$curPageFull = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$curPage = explode("?", $curPageFull);


		$cfg = $this->f3->get('cfg');
		unset($cfg['DB']);
		$_v = isset($_GET['v']) ? $_GET['v'] : $this->f3->get('v');
		$this->vars['_isLocal'] = isLocal();
		$this->vars['_version'] = $this->f3->get('version');

		$this->vars['_v'] = $_v;
		$this->vars['_cfg'] = $cfg;
		$this->vars['_folder'] = $this->vars['folder'];




		//test_array(	$this->vars['_domains']); 



		return $this->render_template();




	}

	public function render_template() {
		$this->default_vars();
		if (is_array($this->vars['folder'])){
			$folder = $this->vars['folder'];
		} else {
			$folder = array(
				"ui/",
				$this->vars['folder']
			);
		}


		if (isset($this->vars['page'])){
			if (isset($this->vars['page']['template'])){

				$folders = $folder;
				$tfile = $this->vars['page']['template'];
				$tfile = explode(".", $tfile);
				$tfile = $tfile[0];

				foreach ($folders as $f) {

					if (file_exists('' . $f . '' . $tfile . '.tmpl')) {
						if (file_exists('' . $f . '_js/' . $tfile . '.js')) {
							$this->vars['page']['template_js'] = '/' . $f . '_js/' . $tfile . '.js';
						}
						if (file_exists('' . $f . '_css/' . $tfile . '.css')) {
							$this->vars['page']['template_css'] = '/' . $f . '_css/' . $tfile . '.css';
						}
						if (file_exists('' . $f . 'templates/' . $tfile . '.jtmpl')) {
							$this->vars['page']['template_jtmpl'] = '/'  . 'templates/' . $tfile . '.jtmpl';
						}
						break;


					}








				}
			}
			//test_array($this->vars['page']);
		}



		if ($this->config['strictfolder']){
			$folder = $this->vars['folder'];
		}

		$loader = new Twig_Loader_Filesystem($folder);

		$options = array();
		if (!isLocal() && $this->f3->get("CACHE")){
			$options['cache'] = $this->config['cache_dir'];

		}
		$options['debug'] = true;

		$base64_encode = new Twig_SimpleFilter('base64_encode', function ($string) {
				return @base64_encode($string);
			});
		$base64_decode = new Twig_SimpleFilter('base64_decode', function ($string) {
				$string = @base64_decode($string);
				return $string;
			});
		$url_decode = new Twig_SimpleFilter('url_decode', function ($string) {
				return @urldecode($string);
			});

		$return_decode = new Twig_SimpleFilter('return_decode', function ($string) {
				$string = urldecode($string);
				$string = base64_decode($string);

				return ($string);
			});
		

		$pad = new Twig_SimpleFilter('pad', function ($input, $length, $string='') {

				$string = str_repeat($string,$length) .$input ;
				//test_array($string); 
				return $string;
			}
		);

		$int = new Twig_SimpleFilter('int', function ($input) {

				$string = number_format($input,0) ;
				//test_array($string); 
				return $string;
			}
		);



		//test_array($this->vars); 



		$twig = new Twig_Environment($loader, $options);
		$twig->addFilter($base64_encode);
		$twig->addFilter($base64_decode);
		$twig->addFilter($url_decode);
		$twig->addFilter($return_decode);
		$twig->addFilter($pad);
		$twig->addFilter($int);
		$twig->addExtension(new Twig_Extension_StringLoader());
		$twig->addExtension(new Twig_Extension_Debug());



		//test_array(array("template"=>$this->template,"vars"=>$this->vars));

		return $twig->render($this->template, $this->vars);


	}

	public function render_string() {
		$loader = new Twig_Loader_String();
		$twig = new Twig_Environment($loader);

		return $twig->render($this->vars['template'], $this->vars);
	}


	public function output() {
		$this->f3->set("__runTemplate", true);
		$return = $this->load();
		echo $return;

	}
	public function front_output($data) {
		$this->f3->set("__runTemplate", true);
		$return = $this->load();
		$timer = new timer();
		$return = \models\string::replace($return, $data);
		$timer->stop("Variable Replace");
		echo $return;

	}

}
