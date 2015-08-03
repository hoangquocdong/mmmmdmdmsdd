<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$info =  isset($_REQUEST['info'])? $_REQUEST['info'] : '';

$start = microtime(true);

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    update_userinfo($userid, $info);

	function update_userinfo($userid, $info) {

		$returnarray = array(
			'status' => 500,
			'content'=>	'Update info fail!'
		);

		$userdata = json_decode($info);

		$full_name = clean_text($userdata ->{'fullname'});
		$id_pwc = (int)clean_text($userdata ->{'pcid'});
		$pcname = clean_text($userdata ->{'pcname'});
		$id_investor = (int)clean_text($userdata ->{'investorid'});
		$id_sub = (int)clean_text($userdata ->{'subid'});
		//$password = clean_text($userdata ->{'password'});
		$office_name = clean_text($userdata ->{'officename'});
		//$department_name = clean_text($userdata ->{'fullname'});
		$phone_number = clean_text($userdata ->{'phone'});
		$email = clean_text($userdata ->{'email'});
		$enable = (int)clean_text($userdata ->{'enable'});
		$last_visit = clean_text($userdata ->{'lastvisit'});
		$visit_number = (int)clean_text($userdata ->{'visitnumber'});
		$register_date = clean_text($userdata ->{'regdate'});
		$permission = clean_text($userdata ->{'permission'});
		$writable = (int)clean_text($userdata ->{'writable'});
		$usertype = (int)clean_text($userdata ->{'usertype'});
		$editable = (int)clean_text($userdata ->{'editable'});

		//die ($permission);

		$sql = 'UPDATE `user` 
				SET `id_pwc`='.$id_pwc.',`id_investor`='.$id_investor.',
				`id_sub`='.$id_sub.',`full_name`="'.$full_name.'",
				`office_name`="'.$office_name.'",`phone_number`="'.$phone_number.'",
				`email`="'.$email.'",`enable`='.$enable.',`last_visit`="'.$last_visit.'",`visit_number`='.$visit_number.',
				`register_date`="'.$register_date.'",`permission`="'.$permission.'",
				`writable`='.$writable.',`usertype`='.$usertype.',`editable`='.$editable.',`flag_change`=1 
				WHERE `ID`='.$userid;

		// $sql = 'UPDATE `user` 
		// 		SET `usergroup`=1,`id_pwc`=1,`id_investor`=1,
		// 		`id_sub`=1,`full_name`="Quốc Đông",`user_name`="quocdong",`password`="202cb962ac59075b964b07152d234b70",
		// 		`office_name`="npc",`department_name`="tdh",`phone_number`="0936668484",
		// 		`email`="email@email.com",`enable`=1,`last_visit`="02/04/14 10:55:37",`visit_number`=111,
		// 		`register_date`="02/04/14 10:55:37",`currentpos`="",`token`="03224b341e0ab7c5a2",`permission`="",
		// 		`writable`=1,`usertype`=1,`editable`=1,`cache`=1,
		// 		`cacheall`="",`flag_change`=1,`phongban`="tdh" 
		// 		WHERE `ID`='.$userid;

		$result = mysql_query($sql) or die(json_encode($returnarray));

		$returnarray = array(
			'status' => 200,
			'content'=>	'Update info succesfully!'
		);

		echo json_encode($returnarray);	
	}

	CLOSE_DB();
	unset($sql, $result);
          
?> 