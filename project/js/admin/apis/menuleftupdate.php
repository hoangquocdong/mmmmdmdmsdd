<?php

/*

	*	Usage: call to update left menu 

*/

include('menuleftfnc.php');
$menuupdate = amrupdatecache($id, $token);
include('menuleftfncall.php');
$menuupdate = updatecache($id, $token);
unset($menuupdate);
?> 