<?php
//Connect to database with configuration in config.php
function CONNECT_DB() {
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}	
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	mysql_query("SET NAMES 'utf8'");
}
function CLOSE_DB() {
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}	
	mysql_close($link);
}

//get value in database to array with input query string
function GET_DB_ARRAY($query){
	CONNECT_DB();
	$value = array();
	$qry = mysql_query($query) or die (mysql_error());
	if (mysql_num_rows($qry)==0){
		$value=array( 0=> array (0 =>'not found'));
	} else {
		while ($val = mysql_fetch_assoc($qry)){
			$value[] = $val;
		}
	}
   return $value;
}

//get one value in database to variable with input query string and column name
function GET_DB_VALUE($query, $property){
	CONNECT_DB();
	$value = array();
	$qry = mysql_query($query) or die (mysql_error());
	if (mysql_num_rows($qry)==0){
		$value=array( 0=> array ($property =>'not found'));
	} else {
		while ($val = mysql_fetch_assoc($qry)){
			$value[] = $val;
		}
	}
   return $va = isset($value[0][$property])? $value[0][$property] : 'not found';
}

function CHECK_USER($table, $user, $pass){
	CONNECT_DB();
	
	$qry="SELECT * FROM $table WHERE login='$user' AND passwd='".md5($pass)."'";
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			return true;
		}else {
			return false;
		}
	}
}
//Function to sanitize values received from the form. Prevents SQL injection
	function cleanstr($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	function update_viewcount($tbl_name,$postid){
		$update_sql="UPDATE  $tbl_name SET viewcount = viewcount+1 WHERE ID = ".$postid;
		$update_result=mysql_query($update_sql);
	}
?>