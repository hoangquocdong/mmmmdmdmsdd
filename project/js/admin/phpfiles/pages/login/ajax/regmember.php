<?php
//$cont=$_POST['cont'];
//if ($cont=='cont'){
	define('_DO_EXC',true);
//}
require('../../../../donghq.php');
require(HOMEPATH.'libs/db_functions.php');
$userinput=isset($_POST['userreg'])? $_POST['userreg'] : '';
CONNECT_DB();
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
//echo 'connect db successfully!';
//exit();	
	//Sanitize the POST values
	//$.post('ajax/register.php',{fullname:fullname, email:email, username:username, pas:pas
	$fname = clean($_POST['fullname']);
	//$usertype = clean($_POST['usertype']);
	$username = clean($_POST['user']);
	$password = clean($_POST['pass']);
	$re_password = clean($_POST['repass']);
	$email = clean($_POST['email']);
	
	//Check for duplicate user from unactive_user
	if($username != '') {
		$qry = "SELECT * FROM unactive_user WHERE username ='$username'";//table user - table chứa thông tin user/member
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				echo 'Tên đăng nhập này đã tồn tại, xin vui lòng chọn tên đăng nhập khác!';
				exit();
			}
			@mysql_free_result($result);
		}
		else {
			die("Check user Query failed");
		}
	}
	//Check for duplicate user from user
	if($username != '') {
		$qry = "SELECT * FROM user WHERE username ='$username'";//table user - table chứa thông tin user/member
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				echo 'Tên đăng nhập này đã tồn tại, xin vui lòng chọn tên đăng nhập khác!';
				exit();
			}
			@mysql_free_result($result);
		}
		else {
			die("Check user Query failed");
		}
	}

	//Check for duplicate email from unactive_user
	if($email != '') {
		$qry = "SELECT * FROM unactive_user WHERE email='$email'";//table user - table chứa thông tin user/member
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				echo 'Hòm thư này đã được đăng ký!';
				exit();
			}
			@mysql_free_result($result);
		}
		else {
			die("Check email Query failed");
		}
	}
	//Check for duplicate email from user table
	if($email != '') {
		$qry = "SELECT * FROM user WHERE email='$email'";//table user - table chứa thông tin user/member
		$result = mysql_query($qry);
		if($result) {
			if(mysql_num_rows($result) > 0) {
				echo 'Hòm thư này đã được đăng ký!';
				exit();
			}
			@mysql_free_result($result);
		}
		else {
			die("Check email Query failed");
		}
	}

	//Check retype password
	if($password != $re_password) {
		echo 'Nhập lại password!';
		exit();
	}
	//Create INSERT query
	$confirm_code=md5(uniqid(rand()));
	$dt = new DateTime();
	$dt = (int)date_format($dt, 'dmY');
	$qry = "INSERT INTO unactive_user (code_confirm,fullname, username, password, email, regdate) VALUES('$confirm_code','$fname','$username','".md5($password)."','$email','$dt')";
	$result = @mysql_query($qry);
	
	//Check whether the query was successful or not


	if($result) {
		// send e-mail to ...
		$to=$email;
		// Your subject
		$subject="Thư Kích Hoạt Tài Khoản";
		// From
		$header="from: 'donghq'<donghq@howtojoomlatemplate.com>";
		// Your message
		$message="Chào bạn $fname \r\n";
		$message.="Đây là thư kích hoạt tài khoản bạn đã đăng ký ở diễn đàn BÉ BI BÔ, hãy click vào link sau để kích hoạt tài khoản\r\n";
		$message.="http://www.yourweb.com/confirmation.php?passkey=$confirm_code \r\n";
		// send email
		$sentmail = mail($to,$subject,$message,$header);
		// if your email succesfully sent
		if($sentmail){
			echo " Email kích hoạt tài khoản đã được gửi vào hòm thư của bạn!";
		}
		else {
			echo "Không thể gửi mail, bạn hãy kiểm tra lại hòm thư đăng ký!";
		}
	}
		//echo 'Member register successfully';
		//header('Location: regdone.php');
		exit();
?>