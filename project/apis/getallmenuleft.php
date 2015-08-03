<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id =  isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';

  CONNECT_DB();
    mysql_query("SET NAMES utf8");


	$sql = 'SELECT `cacheall` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id.' AND `flag_change` = 10';
	$result = mysql_query($sql) or die('0');
	
	if (mysql_num_rows($result)){
		$rows = mysql_num_rows($result);
		if ($rows == 1) { 
			while($rsl=mysql_fetch_array($result)){
				$val = $rsl['cacheall'];
		    }
		    echo $val;
		} else {
			echo $menu = json_encode(updatecache($id, $token));
		}
	} else {
		echo $menu = json_encode(updatecache($id, $token));
	}

	function updatecache($id, $token){

		$valuereturn = array();

		$listpc = getallpc();
		
		//echo json_encode($listpc); exit();

		$sql = 'SELECT `permission` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;//.' AND `flag_change` = 1';
		
		$result = mysql_query($sql) or die('0');
		
		$permission ='';	//init value to json_encode function

		while($rsl=mysql_fetch_array($result)){
			$permission = $rsl['permission'];
	    }
	    $permission = json_decode($permission);
	    
	    /*
	    * for theo điện lực có quyền xem - permission
	    */
	    for ($i=0; $i<sizeof($permission); $i++){

	    	$tmp0 = array(); $tmp1 = array();
	    	$pckey = 'idpc_'.$permission[$i][0];

	    	if (isset($listpc[$pckey])){
	    		//array_push($valuereturn, $listpc[$pckey]);
	    		//$pcitem = $listpc[$pckey]; 
	    		$pcitem = array();
	    		$subitem = array();
	    		//echo json_encode( $sublist = getsublist($permission[$i][0]) );
	    		$sublist = getsublist($permission[$i][0]);
	    		for ($j=0; $j<sizeof($permission[$i][1]); $j++){

	    			$subkey = 'idsub_'.$permission[$i][1][$j];
	    			//$subtmp = array();

	    			if (isset($sublist[$subkey])){
	    				array_push($subitem, $sublist[$subkey]);
	    			}
	    			//array_push($pcitem, $listpc[$pckey];);
	    		}
	    		array_push($pcitem, $listpc[$pckey], $subitem);
	    		array_push($valuereturn, $pcitem);
	    	}


	    	/*
	    	for ($j=0; $j<sizeof($listpc); $j++){

	    		//lặp từng điện lực trong permission so sánh với danh sách tất cả các điện lực
	    		if ($listpc[$j]['id_pwc'] == $permission[$i][0]){

	    			$tmp0 = array(); $tmp1 = array();
	    			array_push($tmp0, $listpc[$j]['id_pwc']);
	    			array_push($tmp0, $listpc[$j]['name_pwc']);

	    			// Lặp từng nhà máy để so sánh lấy ra nhà máy điện được quyền xem
		    		for ($k=0; $k<sizeof($permission[$i][1]); $k++){

		    			array_push($tmp1, $listpc[$j]['sub'][$k]);
		    			
		    		}
		    		array_push($tmp0, $tmp1);
		    		array_push($valuereturn, $tmp0);
		    	}
	    	}
	    	*/
	    }



/*

	    for ($i=0; $i<sizeof($permission); $i++){
	    	for ($j=0; $j<sizeof($listpc); $j++){

	    		//lặp từng điện lực trong permission so sánh với danh sách tất cả các điện lực
	    		if ($listpc[$j]['id_pwc'] == $permission[$i][0]){

	    			$tmp0 = array(); $tmp1 = array();
	    			array_push($tmp0, $listpc[$j]['id_pwc']);
	    			array_push($tmp0, $listpc[$j]['name_pwc']);

	    			// Lặp từng nhà máy để so sánh lấy ra nhà máy điện được quyền xem
		    		for ($k=0; $k<sizeof($permission[$i][1]); $k++){

		    			array_push($tmp1, $listpc[$j]['sub'][$k]);
		    			
		    		}
		    		array_push($tmp0, $tmp1);
		    		array_push($valuereturn, $tmp0);
		    	}
	    	}
	    		
	    }
	    */

	    $menu = json_encode($valuereturn);
		update_cache_menu($menu, $token, $id);
	    return $valuereturn;
	}

	function getallpc(){

		$sql='SELECT `id_pwc`, `name_pwc` FROM `power_company` WHERE 1 ORDER BY `id_orderby` ASC'; 
		$result = mysql_query($sql) or die('0');

		$data = array();
		while($row = mysql_fetch_array($result)){

			//$listsub = getsublist($row['id_pwc']);

			$tmp = array(
					'id_pwc' => $row['id_pwc'], 
					'name_pwc' => $row['name_pwc']
					//'sub' => $listsub
				);

			$key = 'idpc_'.$row['id_pwc'];
			$data[$key] = $tmp;
		}
		

		return $data;
	}


	function getsublist($pcid){

		$sub = array();

		    $sql=' SELECT `id_sub`, `name_sub`, `connection_sub`, `status` FROM `substation_power` WHERE `id_pwc` = '.$pcid;//.' AND `status` = 1'; 
		    $result = mysql_query($sql) or die('0');

			while($row = mysql_fetch_array($result)){
				$tmp = array(
						'id_sub' => $row['id_sub'], 
						'name_sub' => $row['name_sub'],
						'online' => $row['connection_sub'],
						'status' => $row['status']
					);
				//array_push($sub, $tmp);
				$key = 'idsub_'.$row['id_sub'];
				$sub[$key] = $tmp;
			}

		return $sub;
	}

	function update_cache_menu($str, $token, $id){
		$value = mysql_real_escape_string($str);
		$sql = 'UPDATE `user` SET `cacheall`="'.$value.'", `flag_change`=0 WHERE token="'.$token.'" AND ID = '.$id;
    	$result = mysql_query($sql) or die('0');
	}

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 