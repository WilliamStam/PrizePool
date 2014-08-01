<?php


function toAscii($str, $replace=array(), $delimiter='-') {
	if( !empty($replace) ) {
		$str = str_replace((array)$replace, ' ', $str);
	}

	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

	return $clean;
}


function isLocal() {
	if (file_exists("D:/web/local.txt") || file_exists("C:/web/local.txt")) {
		return true;
	} else return false;
}

function clean_url($url){

	if (strpos($url, "?")) {
		$url = substr($url, 0, strpos($url, "?"));
	}
	return $url;
}
function return_url($url){

	$return = base64_encode($url);
	$return = urlencode($return);
	return $return;
}



function highlight($needle, $haystack) {
	$ind = stripos($haystack, $needle);
	$len = strlen($needle);
	if ($ind !== false) {
		return substr($haystack, 0, $ind) . "<span class='highlight'>" . substr($haystack, $ind, $len) . "</span>" . highlight($needle, substr($haystack, $ind + $len));
	} else return $haystack;
}
function is_bot() {
	$botlist = array(
		"Teoma",
		"bingbot",
		"alexa",
		"froogle",
		"Gigabot",
		"inktomi",
		"looksmart",
		"URL_Spider_SQL",
		"Firefly",
		"NationalDirectory",
		"Ask Jeeves",
		"TECNOSEEK",
		"InfoSeek",
		"WebFindBot",
		"girafabot",
		"crawler",
		"www.galaxy.com",
		"Googlebot",
		"Googlebot",
		"Scooter",
		"Slurp",
		"msnbot",
		"appie",
		"FAST",
		"WebBug",
		"Spade",
		"ZyBorg",
		"rabaz",
		"Baiduspider",
		"Feedfetcher-Google",
		"TechnoratiSnoop",
		"Rankivabot",
		"Mediapartners-Google",
		"Sogou web spider",
		"WebAlta Crawler",
		"TweetmemeBot",
		"Butterfly",
		"Twitturls",
		"Me.dium",
		"Twiceler"
	);

	foreach ($botlist as $bot) {
		if (strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false) return true; // Is a bot
	}

	return false; // Not a bot
}

function sanitize_output($buffer) {
	$search = array(
		'/\>[^\S ]+/s',
		//strip whitespaces after tags, except space
		'/[^\S ]+\</s',
		//strip whitespaces before tags, except space
		'/(\s)+/s'
		// shorten multiple whitespace sequences
	);
	$replace = array(
		'>',
		'<',
		'\\1'
	);
	//$buffer = preg_replace($search, $replace, $buffer);
	return $buffer;
}

function rev_nl2br($string, $line_break = PHP_EOL) {
	/*	$string = preg_replace('#<\/p>#i', "", $string);
		$string = preg_replace('#<p>#i', "", $string);

	*/
	return $string;


}

function is_ajax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}


function siteURL() {
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER['HTTP_HOST'];
	return $protocol . $domainName;
}

function file_size($size) {
	$unit = array(
		'b',
		'kb',
		'mb',
		'gb',
		'tb',
		'pb'
	);
	return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

function timesince($tsmp) {
	if (!$tsmp) return "";
	$diffu = array(
		'seconds' => 2,
		'minutes' => 120,
		'hours'   => 7200,
		'days'    => 172800,
		'months'  => 5259487,
		'years'   => 63113851
	);
	$diff = time() - strtotime($tsmp);
	$dt = '0 seconds ago';
	foreach ($diffu as $u => $n) {
		if ($diff > $n) {
			$dt = floor($diff / (.5 * $n)) . ' ' . $u . ' ago';
		}
	}
	return $dt;
}

function curl_get_contents($url) {
	$ch = curl_init();
	$timeout = 5; // set to zero for no timeout
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);

	return $file_contents;
}

function currency($number) {

	$number = $GLOBALS['cfg']['currency']['sign'] . number_format($number, 2, '.', $GLOBALS['cfg']['currency']['separator']);
	return str_replace(" ", "&nbsp;", $number);

}

function test_array($array) {
	header("Content-Type: application/json");
	$f3 = \Base::instance();
	$f3->set("__testJson",true);
	echo json_encode($array);
	exit();
}

function test_string($array) {
	header("Content-Type: text/html");
	$f3 = \Base::instance();
	$f3->set("__testString",true);
	echo $array;
	exit();
}

function bt_loop($trace) {
	if (isset($trace['object'])) unset($trace['object']);
	if (isset($trace['type'])) unset($trace['type']);


	$args = array();
	foreach ($trace['args'] as $arg) {
		if (is_array($arg)) {
			if (count($arg) < 5) {
				$args[] = $arg;
			} else {
				$args[] = "Array " . count($arg);
			}

		} else {
			$args[] = $arg;
		}

	}
	$trace['args'] = $args;

	return $trace;
}


function sortBy(&$arr, $col, $dir = SORT_ASC) {
	$sort_col = array();
	foreach ($arr as $key=> $row) {
		$sort_col[$key] = $row[$col];
	}

	array_multisort($sort_col, $dir, $arr);
}



function filter(&$value) {
	$value = (is_string($value)) ?htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
}
function form_display(&$value) {
	if ($value){
		//$value = utf8_encode($value);
		$value = str_replace('"',"&quot;",$value);
		//$value = str_replace('Ã«',"ë",$value);
		//$value = htmlentities($value,'','UTF-8');
	}

}