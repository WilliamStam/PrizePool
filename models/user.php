<?php
namespace models;
use \timer as timer;

class user extends _ {

	function __construct() {
		parent::__construct();
	}
	function get($ID){
		$timer = new timer();

		
		$result = $this->f3->get("DB")->exec("SELECT * FROM users WHERE ID = '$ID';");

		if (count($result)){
			$result = $result[0];

			$return = $result;
		} else {
			$return = parent::dbStructure("users");
		}

		



		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}
	function user($ID){
		$timer = new timer();
		$return = $this->get($ID);
		

		if ($return['ID']){
			$userLocations = $return['locations'];
			if ($userLocations){
				$locations = locations::getAll("ID in ($userLocations)","label ASC");
			} else {
				$locations = array();
			}

			if (!$return['location']){
				$return['location'] = isset($locations[0]['ID'])?$locations[0]['ID']:"";
			}

			$location = new locations();
			$location = $location->get($return['location']);
			$return['location'] = $location;
			$return['locations'] = $locations;

			$return['draws'] = draws::getAll("locationID='".$location['ID']."' AND winnerID is NULL","datein ASC");
			$return['draw'] = $return['draws'][0];
			
			if ($return['draw']['ID']){
				$return['draw']['totals'] = sales::getTotals("draws.ID='".$return['draw']['ID']."'");
				
			}

			//test_array($return); 
		}
		
		

		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}
	
	function login($username,$password){
		
		
		$result = $this->f3->get("DB")->exec("SELECT * FROM users WHERE username = '$username' AND password = '$password';");
		
		if (count($result)){
			$result = $result[0];
		}
		
		
		if (isset($result['ID']) && $result['ID']){
			$_SESSION['uID'] = base64_encode($result['ID']);
			if ($username) {
				setcookie('username', $username, time() + (86400 * 30)); // 86400 = 1 day
			}
			
		}

		
		return isset($result['ID'])?$result['ID']:"";
	}
	public static function _save($ID, $values) {
		$timer = new timer();
		$f3 = \Base::instance();

		$a = new \DB\SQL\Mapper($f3->get("DB"), "users");
		$a->load("ID='$ID'");

		foreach ($values as $key => $value) {
			if (isset($a->$key)) {
				if ($value=="++"){
					$a->$key++;
				} else {
					if ($value == "NULL" || $value == "") $value = NULL;
					$a->$key = $value;
				}

			}
		}

		$a->save();
		$ID = ($a->ID) ? $a->ID : $a->_id;


		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $ID;
	}

	public static function _delete($ID) {
		$timer = new timer();
		$f3 = \Base::instance();

		$a = new \DB\SQL\Mapper($f3->get("DB"), "users");
		$a->load("ID='$ID'");
		$a->erase();
		//test_array($ID);

		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return "done";
	}
}
