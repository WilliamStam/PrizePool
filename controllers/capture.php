<?php
namespace controllers;

class capture extends _ {
	function __construct(){
		parent::__construct();
		
		if (!$this->user['ID'])$this->f3->reroute("/login");
	}
	function page(){
		$page = array(
			"template" => "capture.tmpl", 
			"section" => "capture", 
			"title" => "Capture"
			
		);

		$tmpl = new \template("_.tmpl", "ui/front/");
		$tmpl->page = $page;
		$tmpl->output();
	}
}
 