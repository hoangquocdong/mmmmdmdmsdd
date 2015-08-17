<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$username =  isset($_REQUEST['username'])? $_REQUEST['username'] : '';
$password = isset($_REQUEST['password'])? $_REQUEST['password'] : '';

$username = clean_text($username);
$password = clean_text($password);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $returnvalue = '';
	
	$sql = 'SELECT `currentpos` FROM `useradmin2015`  WHERE password = "'.MD5($password).'" AND user_name = "'.$username.'" AND enable = 1';
    $result = mysql_query($sql) or die('0');
    
    while($rsl=mysql_fetch_array($result)){
        $returnvalue = $rsl['currentpos'];
    }


	$crposarr = explode(',', $returnvalue);

	if (isset($crposarr[0])){ $crpage = $crposarr[0];} else {$crpage = 'qldx';}
	if (isset($crposarr[1])){ $crmenu = $crposarr[1];} else {$crmenu = '';}

	$pages = array('qldx', 'tkvh', 'csdl', 'qlnm');
	if (in_array($crpage, $pages)) {
	    $crpage = 'qldx';
	}
	$pages = array('htdx', 'tbcb', 'tsvh', 'tspt', 'tscs', 'csct', 'tonghop', 'giambd', 'qlttnm', 'qltbdl', 'qlhsnm');
	if (in_array($crmenu, $pages)) {
	    $crmenu = '';
	}

	$token = $password.' - '.date("d/m/y H:i:s"); 
	$token = substr(MD5($token),0,18);
	$sql='SELECT ID, full_name, phone_number, email, writable, usertype, editable FROM useradmin2015 WHERE password = "'.MD5($password).'" AND user_name = "'.$username.'" AND enable = 1';

	$rows = 0;$rs=array();
	
	if ($result=mysql_query($sql)){
		$rows = mysql_num_rows($result);
	}
	
	$userid=0; $fullname = '';
	if ($rows == 1){

		while($rsl=mysql_fetch_array($result)){
			$userid= $rsl['ID'];
			$fullname= $rsl['full_name'];
			$rs=array(
				"link" => 'index.php?page='.$crpage.'#'.$crmenu,
				"id" => $rsl['ID'],
				"fullname" => $rsl['full_name'],
				"phone" => $rsl['phone_number'],
				"email" => $rsl['email'],
				"editable" => $rsl['editable'],
				"writable" => $rsl['writable'],
				"usertype" => $rsl['usertype'],
				"token" => $token		
			);

			$_SESSION['id']=$rsl['ID'];
			$_SESSION['fullname']=$rsl['full_name'];
			$_SESSION['phone']=$rsl['phone_number'];
			$_SESSION['email']=$rsl['email'];
			$_SESSION['editable']=$rsl['editable'];
			$_SESSION['writable']=$rsl['writable'];
			$_SESSION['usertype']=$rsl['usertype'];
			$_SESSION['token']=$token;
			$_SESSION['login']=1;
			//$_SESSION['username']=$username;

	    }
	    echo json_encode($rs);

		$sql = 'UPDATE `useradmin2015` SET `token`="'.$token.'" WHERE ID = '.$rs['id'];
		$result = mysql_query($sql) or die('0');

		$actioncode = 1; //'đăng nhập'
		//userlogs($userid, $fullname, $action);
		useradminlogs($useid, $actioncode);

	} else { echo "0";}

	CLOSE_DB();
	unset($sql, $result, $rs, $rows);
          
?> 