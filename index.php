<?php
$GLOBALS["models"] = array();
$GLOBALS["output"] = array();
$GLOBALS["render"] = "";
if (session_id() == "") {
	$SID = @session_start();
} else $SID = session_id();
if (!$SID) {
	session_start();
	$SID = session_id();
}
// loading the config and default config
$cfg = array();
require_once('config.default.inc.php');
require_once('config.inc.php');
date_default_timezone_set($cfg['TZ']);
$GLOBALS['cfg'] = $cfg;
//$app = require('lib/f3/base.php');




require_once('inc/functions.php');
require_once('inc/app.php'); // core
require_once('inc/error.php'); // core
require_once('inc/class.pagination.php');
require_once('lib/Twig/Autoloader.php');
Twig_Autoloader::register();
require_once('inc/class.template.php');
require_once('inc/class.timer.php');

// the auto loader folders
$autoload = array(
	"./",
	"lib/",
	"controllers/"
);

$app = new app();
app::pageStart();

// loop through each app in the apps directory to build up the auto load string and app list

// read the current git version string and convert it to numbers to be used for cache busting
$version = date("YmdH");
if (file_exists("./.git/refs/heads/" . $cfg['git']['branch'])) {
	$version = file_get_contents("./.git/refs/heads/" . $cfg['git']['branch']);
	$version = substr(base_convert(md5($version), 16, 10), -10);
}
$minVersion = preg_replace("/[^0-9]/", "", $version);
$app->set('version', $version);
$app->set('v', $minVersion);
//test_array($version); 


$app->set('AUTOLOAD', implode("|", $autoload));
$app->set('PLUGINS', 'lib/f3/|lib/mods/');
$app->set('TZ', $cfg['TZ']);
$app->set('DEBUG', 2);
$app->set('HIGHLIGHT', FALSE);
$app->set('UNLOAD', 'app::pageEnd');
$app->set('ONERROR', 'Error::handler');

$app->set('UI', 'ui/;');
$app->set('CACHE', false);

$app->set('DB', new DB\SQL('mysql:host=' . $cfg['DB']['host'] . ';dbname=' . $cfg['DB']['database'] . '', $cfg['DB']['username'], $cfg['DB']['password']));

$app->set('CFG', $cfg);
$app->set('VERSION', $version);


$uID = isset($_SESSION['uID']) ? $_SESSION['uID'] : "";
if ($uID){
	$uID = base64_decode($uID);
}

$username = isset($_POST['login_username']) ? $_POST['login_username'] : "";
$password = isset($_POST['login_password']) ? $_POST['login_password'] : "";

$userO = new \models\user();
//$uID = "2";




if ($username && $password) {

	$uID = $userO->login($username, $password);
	
}
$user = $userO->user($uID);
if (isset($_GET["L"])){
	models\user::_save($user['ID'],array("location"=>$_GET['L']));
	$user = $userO->user($user['ID']    );
}


//test_array($user); 


$app->set('user', $user);




$app->route('GET /min/javascript*', '_js->dump');


$app->route('GET|POST /login', 'controllers\login->page');
$app->route('GET|POST /', function($f3){
		$f3->reroute("/capture");
	});
$app->route('GET|POST /capture', 'controllers\capture->page');

$app->route('GET|POST /draws', 'controllers\draws->page');
$app->route('GET|POST /members', 'controllers\members->page');
$app->route('GET|POST /locations', 'controllers\locations->page');




$app->route("GET|POST /save/@function", function ($app, $params) {
		$app->call("controllers\\save\\save->" . $params['function']);
	}
);
$app->route("GET|POST /save/@class/@function", function ($app, $params) {
		$app->call("controllers\\save\\" . $params['class'] . "->" . $params['function']);
	}
);
$app->route("GET|POST /save/@folder/@class/@function", function ($app, $params) {
		$app->call("controllers\\save\\" . $params['folder'] . "\\" . $params['class'] . "->" . $params['function']);
	}
);
$app->route("GET|POST /data/@function", function ($app, $params) {
		$app->call("controllers\\data\\data->" . $params['function']);
	}
);
$app->route("GET|POST /data/@class/@function", function ($app, $params) {
		$app->call("controllers\\data\\" . $params['class'] . "->" . $params['function']);
	}
);
$app->route("GET|POST /data/@folder/@class/@function", function ($app, $params) {
		$app->call("controllers\\data\\" . $params['folder'] . "\\" . $params['class'] . "->" . $params['function']);
	}
);





$app->route('GET /keepalive', function ($app, $params) use ($user) {


		$last_activity = new DateTime($user['last_activity']);
		$now = new DateTime('now');

		$interval = $last_activity->diff($now);
		$diff = (($interval->h * 60) * 60) + ($interval->i * 60) + ($interval->s);

		//$interval['diff']=$diff;


		if (isset($_GET['keepalive']) && $_GET['keepalive']) {
			$app->get("DB")->exec("UPDATE global_users SET last_activity = now() WHERE ID = '" . $user['ID'] . "'");
			$diff = 0;
			// upadate the last_activity
		}
		$t = array("ID" => $user['ID'], "idle" => $diff);

		test_array($t);

	}
);

$app->route('GET|POST /logout', function ($app, $params) use ($user) {
		session_unset();
		//session_destroy();
		$app->reroute("/login");
	}
);





//test_array($app->routes);

$app->route('GET /php', function () {
		phpinfo();
		exit();
	}
);
$app->route('GET /redirect', function () {
		$url = isset($_GET['url'])?$_GET['url']:"";
		
		if (!$url){
			$url = "/";
		}
		$f3 = Base::instance();
		$f3->reroute($url);
	}
);


$app->run();

