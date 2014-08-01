<?php
namespace models;
use \timer as timer;

class sales extends _ {

	function __construct() {
		parent::__construct();
	}
	function get($ID){
		$timer = new timer();

		
		$result = $this->f3->get("DB")->exec("
			SELECT sales.* 
			FROM sales LEFT JOIN draws ON sales.drawID = draws.ID WHERE ID = '$ID';");

		if (count($result)){
			$result = $result[0];

			$return = $result;
		} else {
			$return = parent::dbStructure("sales");
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
			SELECT sales.*, draws.label AS drawName
			FROM (sales INNER JOIN draws ON sales.drawID = draws.ID)
			$where
			$orderby
			$limit

		", $options['args'],$options['ttl']);


		$return = $result;


		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}
	public static function getTotals($where = "", $orderby = "", $limit = "", $options = array()){
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
			SELECT sum(sales.num_tickets) as num_tickets, sum(sales.val_tickets) as val_tickets
			FROM (sales INNER JOIN draws ON sales.drawID = draws.ID)
			$where
			$orderby
			$limit

		", $options['args'],$options['ttl']);


		if (count($result)){
			$result = $result[0];
		}
		$return = $result;


		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return $return;
	}
	public static function _save($ID, $values) {
		$timer = new timer();
		$f3 = \Base::instance();

		$a = new \DB\SQL\Mapper($f3->get("DB"), "sales");
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

		$a = new \DB\SQL\Mapper($f3->get("DB"), "sales");
		$a->load("ID='$ID'");
		$a->erase();
		//test_array($ID);

		$timer->_stop(__NAMESPACE__, __CLASS__, __FUNCTION__, func_get_args());
		return "done";
	}

}
