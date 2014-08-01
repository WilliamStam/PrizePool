<?php
namespace controllers;

class draws extends _ {
	function __construct(){
		parent::__construct();
		
		if (!$this->user['ID'])$this->f3->reroute("/login");
	}
	function page(){
		$page = array(
			"template" => "draws.tmpl", 
			"section" => "draws",
			"title" => "Draws"
			
		);

		$tmpl = new \template("_.tmpl", "ui/front/");
		$tmpl->page = $page;
		$tmpl->output();
	}
}
 