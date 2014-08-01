<?php

class app {
	function __construct(){
		$this->f3 = require('lib/f3/base.php');
	}
	function __destruct(){

	}
	function set($key, $val, $ttl = 0){
		return $this->f3->set($key, $val, $ttl);
	}
	function get($key){
		return $this->f3->get($key);
	}
	function route($pattern, $handler, $ttl = 0, $kbps = 0){
		return $this->f3->route($pattern, $handler, $ttl, $kbps);

	}

	function run(){
		$cfg = $this->f3->get("CFG");
		$app = $this->f3->get("app");
		$user = $this->f3->get("user");

		if (isset($user['ID'])){
			$this->f3->get("DB")->exec("UPDATE users SET last_activity = now() WHERE ID = '" . $user['ID'] . "'");
		}
		
		return $this->f3->run();
	}

	public static function pageStart() {
		$GLOBALS['page_execute_timer'] = new timer(true);
		$f3 = \Base::instance();
		$f3->set("__runTemplate",false);
		ob_start();
	}

	public static function pageEnd() {


		$f3 = \Base::instance();
		if ($f3->get("__testJson")) exit();
		if ($f3->get("__testString")) exit();
		if ($f3->get("ERROR")) exit();

		$GLOBALS["render"] = ob_get_contents();
		$pageSize = ob_get_length();
		ob_end_clean();
		$models = $GLOBALS['models'];
		$t = array();
		foreach ($models as $model) {
			$c = array();
			foreach ($model['m'] as $method) {
				$c[] = $method;
			}
			$model['m'] = $c;
			$t[] = $model;
		}

		$models = $t;
		$pageTime = $GLOBALS['page_execute_timer']->stop("Page Execute");

		$GLOBALS["output"]['timer'] = $GLOBALS['timer'];
		$GLOBALS["output"]['models'] = $models;
		$GLOBALS["output"]['page'] = array(
			"page" => $_SERVER['REQUEST_URI'],
			"time" => $pageTime,
			"size" => ($pageSize)
		);
		//test_array($f3->get("__runTemplate"));

		if ($f3->get("__noPageEnd")){
			echo $GLOBALS["render"];

		} else {

			if (($f3->get("AJAX") && ($f3->get("__runTemplate")==false) || $f3->get("__runJSON"))) {
				header("Content-Type: application/json");

				echo json_encode($GLOBALS["output"]);
			} else {

				$timersbottom = '
					<script type="text/javascript">
				       updatetimerlist(' . json_encode($GLOBALS["output"]) . ');
					</script>
				';
				$content = $GLOBALS["render"];
				if (strpos($GLOBALS["render"], "<!--print version-->") || strpos($GLOBALS["render"], "<!--no_timer_list-->")) {
					//	$content = $GLOBALS["render"];
				} else {
					$content = str_replace("<!--timer_list-->", $timersbottom . '<!--timer_list-->', $content);
				}

				


				//test_string($content); 
				echo $content;
			}
		}


	}

}


