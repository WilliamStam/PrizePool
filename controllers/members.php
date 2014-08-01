<?php
namespace controllers;

class members extends _ {
	function __construct(){
		parent::__construct();
		
		if (!$this->user['ID'])$this->f3->reroute("/login");
	}
	function page(){
		$page = array(
			"template" => "members.tmpl", 
			"section" => "members", 
			"title" => "Members"
			
		);

		$tmpl = new \template("_.tmpl", "ui/front/");
		$tmpl->page = $page;
		$tmpl->output();
	}
}
 