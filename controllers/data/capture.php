<?php
namespace controllers\data;
use models as models;

class capture extends _ {
	function __construct() {
		parent::__construct();

	}
	
	function form(){
		$user = $this->user;
		$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : "";

		$result = array();
		
		
		$result["search"] = $search;

		$member = models\members::getAll("phone LIKE '%$search%'");
		
		if (count($member)){
			$member = $member[0];
		} else {
			$member = models\members::dbStructure("members");
		}

		$member['history']=models\sales::getAll("draws.locationID='".$user['location']['ID']."' AND memberID = '".$member['ID']."'","datein DESC");
		
		
		
		
		$result['member'] = $member;


		return $GLOBALS["output"]['data'] = $result;
	}
	
	
}
?>
 