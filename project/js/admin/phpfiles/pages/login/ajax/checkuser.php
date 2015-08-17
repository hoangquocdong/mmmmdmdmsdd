<?php
session_start();								//không đặt session start ở đây thì gán biến session vẫn ko báo lỗi nhưng ko lưu lại giá trị được.

//$cont=$_POST['cont'];
//if ($cont=='cont'){
//	define('_DO_EXC',true);
//}
require('../../../../donghq.php');
require(HOMEPATH.'libs/db_functions.php');

CONNECT_DB();

$tbl_name='user'; // Table name 
//echo $tbl_name; exit();
//username and password sent from form - user clean function to protect MySQL injection
$myusername=cleanstr($_POST['user']); 
$mypassword=cleanstr($_POST['pass']); 

$sql="SELECT * FROM $tbl_name WHERE user_name='$myusername' and password='".md5($mypassword)."'"; //exit();
$result=mysql_query($sql);
if (!$result){echo 'Error!';exit();}

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);// exit();
//echo 'here';exit();
	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count==1){										//kiểm tra sự tồn tại của user
		$enable = (int)GET_DB_VALUE($sql, 'enable');	//kiểm tra xem user có bị disable không
		$usergroup = (int)GET_DB_VALUE($sql, 'usergroup'); 
		$id_investor = (int)GET_DB_VALUE($sql, 'id_investor');
		$id_pwc = (int)GET_DB_VALUE($sql, 'id_pwc');
		$id_sub = (int)GET_DB_VALUE($sql, 'id_sub');
		//$ma_congto = GET_DB_VALUE($sql, 'ma_congto'); 
		//$user_right = (int)GET_DB_VALUE($sql, 'user_right'); 
		$visitnumber = (int)GET_DB_VALUE($sql, 'visit_number'); 
		if ($enable==1){

			$_SESSION['memberloggedin']=$myusername;
			$_SESSION['usergroup']=$usergroup;
			$_SESSION['id_investor']=$id_investor;
			$_SESSION['id_pwc']=$id_pwc;
			$_SESSION['id_sub']=$id_sub;
			//$_SESSION['user_right']=$user_right;			// cái mô thế này
			$_SESSION['loggedin']=true;

			$visitnumber++;
			$sqlupdate="UPDATE user SET last_visit ='$timenow', visit_number = $visitnumber WHERE user_name='$myusername' and password='".md5($mypassword)."'";
			$updateresult=mysql_query($sqlupdate);
			//header("location:http://bebibo.org/");
			/*
				userlogs here
			*/
			$time_now=strtotime(date("m/d/Y H:i:s"));
			$sql = "INSERT INTO userlogs (username, activities, time) VALUES ('$myusername',1,$time_now)";
			$result=mysql_query($sql);
			/*
				userlogs here
			*/
			echo 'Successful';//.$_SESSION['memberloggedin'];
			
		} else {
			echo 'Tài khoản của bạn đang bị khóa, hãy liên lạc lại với người quản trị.';exit();
		}	
	}
	else {
		echo 'Đăng nhập không thành công! Bạn hãy kiểm tra lại tên đăng nhập hoặc mật mã.';exit();
	}
?>