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

// Normalize path separator to windows format	
$parameters = str_replace('/', '\\', $parameters);

// Open File with default program
exec ('explorer.exe ' . $parameters);

?>