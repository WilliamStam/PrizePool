<?php

$i = 1;
$sql = array(
	$i++ => array(
		"ALTER TABLE  `draws` CHANGE  `target`  `prizePercent` int( 3 ) NULL DEFAULT NULL;"
	)
);