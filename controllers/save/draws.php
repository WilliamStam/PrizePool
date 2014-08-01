<?php
namespace controllers\save;
use models as models;

class draws extends _ {
	function __construct() {
		parent::__construct();

	}
	
	
	function form(){
		$error = array();
		$user = $this->f3->get("user");
		$ID = (isset($_GET['ID']) && $_GET['ID']) ? $_GET['ID'] : "";
		if (in_array($ID, array("undefined"))) $ID = "";

		$values = array(
			"label" => isset($_POST['label']) ? $_POST['label'] : "",
			"locationID" => $user['location']['ID'],

		);

		$errors = array(
			"label"=>"Required",
		);

		foreach ($errors as $k=>$v)if (!$values[$k]) $error[$k] = $v;





		$result = array();
		$result['error'] = $error;
		$result['data'] = array();

		if (!count($error)) {
			$result['data'] = models\draws::_save($ID,$values);

			

			//$result['data'] = array("ID" => $memberID);


		}

		return $GLOBALS["output"]['data'] = $result;
	}
	
	
}
?>
 