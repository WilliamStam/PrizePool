<?php

namespace controllers\save;

use models as models;

class _ extends \controllers\_ {
	function __construct() {
		parent::__construct();
		$this->user = $this->f3->get("user");
		if ($this->user['ID']) {
			
		} else {
			$this->f3->error(403);
		}

		$this->f3->set("__runJSON", true);
	}



}
