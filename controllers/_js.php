<?php

class _js {
	function __construct() {
		$this->f3 = \Base::instance();
	}

	function dump() {
		$expires = 60 * 60 * 24 * 14;
		header("Pragma: public");
		header("Cache-Control: maxage=" . $expires);
		header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');

		$file = (isset($_GET['file'])) ? $_GET['file'] : "";
		//$file = $this->f3->get('PARAMS.filename');
		header("Content-Type: application/javascript");
		$files = array(
			//"/ui/_js/libs/jquery.js",
			"/ui/_js/libs/jquery-ui.js",
			//"/ui/_js/libs/bootstrap.js",
			"/ui/_js/plugins/jquery.hotkeys.js",
			"/ui/_js/plugins/jquery.smooth-scroll.js",
			"/ui/_js/plugins/jquery.jqote2.js",
			"/ui/_js/plugins/jquery.ba-bbq.js",
			"/ui/_js/plugins/jquery-scrolltofixed.js",
			"/ui/_js/plugins/jquery.scrollTo.js",
			"/ui/_js/plugins/jquery.cookie.js",
			"/ui/_js/plugins/select2.js",
			"/ui/_js/plugins/jquery.getData.js",
			"/ui/_js/plugins/jquery-ui-timepicker-addon.js",
			"/ui/_js/plugins/jquery.webticker.js",


			"/ui/_js/scripts.js",
		);

		if (isset($_GET['s'])) {
			$extras = explode(",", $_GET['s']);
			foreach ($extras as $file) {
				$files[] = "/ui/_js/" . $file . ".js";
			}
		}


		//test_array($files);

		$t = "";
		foreach ($files as $file) {
			$fileDetails = pathinfo(($file));
			$base = "." . $fileDetails['dirname'] . "/";
			$file = $fileDetails['basename'];

			$t .= file_get_contents($base . $file);

		}

		$this->f3->set("__noPageEnd", true);

		echo $t;
	}
}
 