<?php
namespace models;
use \timer as timer;

class draws extends _ {

	function __construct() {
		parent::__construct();
	}
	function get($ID){
		$timer = new timer();

		
		$result = $this->f3->get("DB")->exec("SELECT * FROM draws WHERE ID = '$ID';");

		if (count($result)){
			$result = $result[0];

			$return = $result;
		} else {
			$return = parent::dbStructure("draws");
		}

		



		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}
	public static function getAll($where = "", $orderby = "", $limit = "", $options = array()){
		$f3 = \Base::instance();
		$timer = new timer();
		$options = array(
			"ttl"  => isset($options['ttl']) ? $options['ttl'] : "",
			"args" => isset($options['args']) ? $options['args'] : array()
		);

		if ($where) {
			$where = "WHERE " . $where . "";
		} else {
			$where = " ";
		}

		if ($orderby) {
			$orderby = " ORDER BY " . $orderby;
		}
		if ($limit) {
			$limit = " LIMIT " . $limit;
		}
		
		
		
		$result = $f3->get("DB")->exec("
			SELECT * , (SELECT fullName FROM members WHERE members.ID = winnerID) AS winnerName
			FROM draws 
			$where
			$orderby
			$limit

		", $options['args'],$options['ttl']);


		$return = $result;


		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}
	public static function _save($ID, $values) {
		$timer = new timer();
		$f3 = \Base::instance();

		$a = new \DB\SQL\Mapper($f3->get("DB"), "draws");
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

		$a = new \DB\SQL\Mapper($f3->get("DB"), "draws");
		$a->load("ID='$ID'");
		$a->erase();
		//test_array($ID);

		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return "done";
	}

}
