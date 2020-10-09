<?php

// Copyright to Renan LAVAREC - Ti-R
// http://www.ti-r.com
// License LGPL


// Check parameters numbers is enought !
$tCount = $_SERVER['argc']-1;
if($tCount<0)
	return;

// Get all parameters
$parameters='';
for($i=0;$i<$tCount;$i++)
	$parameters .= $_SERVER['argv'][$i+1].' ';

// Execute and keep cmd console open
exec ('cmd.exe /k "' . $parameters .'"');

?>