<?php
//session_start();								//không đặt session start ở đây thì gán biến session vẫn ko báo lỗi nhưng ko lưu lại giá trị được.

//$cont=$_POST['cont'];
//if ($cont=='cont'){
//	define('_DO_EXC',true);
//}
require('../../../donghq.php');
require(HOMEPATH.'libs/db_functions.php');

CONNECT_DB();

$tbl_name='user'; // Table name 

// username and password sent from form - user clean function to protect MySQL injection
$myusername=cleanstr($_POST['user']); 
$mypassword=cleanstr($_POST['pass']); 
//echo $myusername." :: ".$mypassword;//exit();
$sql="SELECT enable FROM $tbl_name WHERE username='$myusername' and password='$mypassword'";
$result=mysql_query($sql);

// Mysql_num_row is counting table row
$count=mysql_num_rows($result);
//echo $count;exit();
	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count==1){										//kiểm tra sự tồn tại của user
		$enable = (int)GET_DB_VALUE($sql, 'enable');	//kiểm tra xem user có bị disable không
		if ($enable==1){
			// Register $myusername, $mypassword and redirect to file "login_success.php"
			//session_start();
			//session_register("name");
			//session_register("myusername");
			//session_register("mypassword"); 
			//$_SESSION['memberloggedin']=$myusername;
			//$_SESSION['loggedin']=true;

			$sqlupdate="UPDATE user SET lastvisit ='$timenow' WHERE username='$myusername' and password='$mypassword'";
			$updateresult=mysql_query($sqlupdate);
			//header("location:http://bebibo.org/");
			echo 'Successful';//.$_SESSION['memberloggedin'];
		} else {
			echo 'Tài khoản của bạn đang bị khóa, hãy liên lạc lại với người quản trị.';exit();
		}
		
	}
	else {
		echo 'Đăng nhập không thành công! Bạn hãy kiểm tra lại tên đăng nhập hoặc mật mã.';exit();
	}
?>