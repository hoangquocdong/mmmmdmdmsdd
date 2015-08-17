<?
	$_SESSION['id']=null;
	$_SESSION['fullname']=null;
	$_SESSION['phone']=null;
	$_SESSION['email']=null;
	$_SESSION['token']=null;
	$_SESSION['login']=0;
	//session_start();
    //session_destroy();
	//chỗ này cho thêm đk kiểm tra SESSION nào đõ nữa như session_is_registered... để tăng tính bảo mật
	//if ($_SESSION['memberloggedin']!='Guest'){		
	//	header("location:index.php");
	//}
/*
	LOAD PAGE
*/
/*
	KHU VỰC PHẢI THAY ĐỔI - KHỞI TẠO - KHỞI TẠO CÁC BIẾN CẦN DÙNG
	khai báo các biến cần truyền cho pages như title, list, nội dung các modun khác...
*/
	global $_content;
	$page_tile = 'Đăng nhập EVN monitoring program!';   //Title của trang đó
	//$menubar=load_modun('menubar');					//truyền các biến cần thiết cho template
	//$link1 = 'index.php';								//code tay gõ vào trong template đc thì ko cần truyền
	//$link2 = 'index.php';								//tuy nhiên việc sửa nội dung lẫn phần sửa giao diện sẽ dễ nhầm lẫn hơn
	//$link3 = 'index.php';
	//$modun1 = load_modun($modun_name);				//các modun nội dung của page
	//$footercontent=load_modun('firstclass_footer');
	//$login_content=load_modun('login_content');

/*
	KHU VỰC KHÔNG THAY ĐỔI - TỰ ĐỘNG - PHẦN TỰ ĐỘNG
	gọi template ra - phần này tự động sử dụng các biến truyền vào.
*/
	$cctpl = new Template($page_template_path.'/default.html');
/*
	KHU VỰC PHẢI THAY ĐỔI THEO CÁC BIẾN ĐÃ KHỞI TẠO/ KHAI BÁO
	Truyền các biến vào nội dung page
*/
	$cctpl -> set('tile',$page_tile);
	$cctpl -> set('path',$page_template_path);
	$cctpl -> set('php_path',$page_phpfiles_path);
	//$cctpl -> set('footercontent',$footercontent);
	//$cctpl -> set('login_content',$login_content);
	
/*
	Lấy nội dung của page trả về một biến php
	Có thể dùng biến toàn cục để hiển thị nội dung trên page index.php
	Hoặc hiển thị trực tiếp kết quả ra
	Ưu nhược điểm:
	Hiển thị trên index.php => bảo mật cao, hoàn toàn ko có phần display nào khác ngoài trang index.php nhưng có lỗi hiển thị do framework chưa hoàn chỉnh.
	Echo ngay tại file include thì hiển thị tốt, tuy nhiên lập trình code phải chú ý không để hiện tượng chạy trực tiếp file include và hiển thị nội dung.
*/
	//echo $page_content = $cctpl->fetch();		//echo tại file include
	$_content = $cctpl->fetch();				//echo tại file index.php
?>