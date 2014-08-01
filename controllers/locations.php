<?php
namespace controllers;

class locations extends _ {
	function __construct(){
		parent::__construct();
		
		if (!$this->user['ID'])$this->f3->reroute("/login");
	}
	function page(){
		$page = array(
			"template" => "locations.tmpl", 
			"section" => "locations", 
			"title" => "Locations"
			
		);

		$tmpl = new \template("_.tmpl", "ui/front/");
		$tmpl->page = $page;
		$tmpl->output();
	}
}
 