<?php
namespace controllers;

class login extends _ {
	function __construct(){
		parent::__construct();
	}
	function page(){
		$page = array(
			"template" => "login.tmpl", 
			"section" => "", 
			"title" => "Please login"
			
		);

		$tmpl = new \template("_.tmpl", "ui/front/");
		$tmpl->page = $page;
		$tmpl->output();
	}
}
 