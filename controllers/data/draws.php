<?php
namespace controllers\data;
use models as models;

class draws extends _ {
	function __construct() {
		parent::__construct();

	}
	
	function details(){
		$user = $this->user;
		$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : "";
		$ID = isset($_REQUEST['ID'])&& $_REQUEST['ID']? $_REQUEST['ID'] : $user['draw']['ID'];

		
		$result = array();

		$details = new models\draws();
		$details = $details->get($ID);
		
		if ($details['winnerID']){
			$u = new models\members();
			$u = $u->get($details['ID']);
			$details['winner'] = $u;
		}
		
		
		$result['details'] = $details;
		$result['list'] = models\draws::getAll("draws.locationID = '".$user['location']['ID']."'","datein DESC");
		
		
		
		
		$result['totals'] = models\sales::getTotals("draws.ID = '".$details['ID']."'");

		if (isset($result['details']['ID']) && $result['details']['ID']){
			if (isset($result['totals']['val_tickets'])&&$result['totals']['val_tickets']){
				$result['details']['percent'] = ($result['totals']['val_tickets'] / $result['details']['target'])*100;
			}


		}
		
		
		
		


		return $GLOBALS["output"]['data'] = $result;
	}
	function form(){
		$user = $this->user;
		$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : "";
		$ID = isset($_REQUEST['ID'])&& $_REQUEST['ID']? $_REQUEST['ID'] : "";

		
		$result = array();

		$details = new models\draws();
		$details = $details->get($ID);


		$result = $details;
		


		return $GLOBALS["output"]['data'] = $result;
	}
	
	
}
?>
 