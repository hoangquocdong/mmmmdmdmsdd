<?php
	session_start();
	//chỗ này cho thêm đk kiểm tra SESSION nào đõ nữa như session_is_registered... để tăng tính bảo mật
	if ($_SESSION['enable']!='enable'){		
		header("location:index.php");
	}
?>

<html>
<body>
Login Successful
</body>
</html>