<?php
namespace controllers\save;
use models as models;

class capture extends _ {
	function __construct() {
		parent::__construct();

	}
	
	function form(){
		$error = array();
		$user = $this->f3->get("user");
		$ID = (isset($_GET['ID']) && $_GET['ID']) ? $_GET['ID'] : "";
		if (in_array($ID, array("undefined"))) $ID = "";

		$values = array(
			"fullname" => isset($_POST['fullname']) ? $_POST['fullname'] : "", 
			"phone" => isset($_POST['phone']) ? $_POST['phone'] : "",
			"num_tickets" => isset($_POST['num_tickets']) ? $_POST['num_tickets'] : "",

		);

		$errors = array(
			"fullname"=>"Required",
			"phone"=>"Required",
			"num_tickets"=>"Required",
		);
		
		foreach ($errors as $k=>$v)if (!$values[$k]) $error[$k] = $v;

		



		$result = array();
		$result['error'] = $error;
		$result['data'] = array();

		if (!count($error)) {
			$memberID = models\members::_save($ID,$values);
			
			$values = array(
				"memberID"=>$memberID,
				"drawID"=>$user['draw']['ID'],
				"userID"=>$user['ID'],
				"num_tickets"=>$values['num_tickets'],
				"val_tickets"=>$values['num_tickets']*$user['location']['price'],
				
			);

			models\sales::_save("",$values);
			
			//$result['data'] = array("ID" => $memberID);


		}
		
		return $GLOBALS["output"]['data'] = $result;
	}
	
	
}
?>
 