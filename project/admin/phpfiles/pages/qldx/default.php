<?
	/*
		*Lấy dữ liệu nhét vào bảng
	*/	
	
	if (!$_SESSION['login']) header('Location: index.php?page=login');//ko cho vào trực tiếp, khi chưa đăng nhập thì tự động chuyển về trang đăng nhập	

	CONNECT_DB();

	mysql_query("SET NAMES utf8");
	
	global $_htmlpath, $userid, $token, $crpage, $crmenu, $crpcid, $crsubid, $crmeter, $crnamesub;

	$_htmlpath = $page_template_path;

	$modunheader = load_modun('header');
	
	//$menuleft = menuleftget($userid, $token);


	/*
		LOAD PAGE
	*/
	/*
		KHU VỰC PHẢI THAY ĐỔI - KHỞI TẠO - KHỞI TẠO CÁC BIẾN CẦN DÙNG
		khai báo các biến cần truyền cho pages như title, list, nội dung các modun khác...
	*/
	global $_content;
	$page_tile = 'EVN Monitoring Program Homepage!';       			//Title của trang đó

	/*
		KHU VỰC KHÔNG THAY ĐỔI - TỰ ĐỘNG - PHẦN TỰ ĐỘNG
		gọi template ra - phần này tự động sử dụng các biến truyền vào.
	*/
	$cctpl = new Template($page_template_path.'/default.html');
	$_htmlpath = $page_template_path;
	/*
		KHU VỰC PHẢI THAY ĐỔI THEO CÁC BIẾN ĐÃ KHỞI TẠO/ KHAI BÁO
		Truyền các biến vào nội dung page
	*/

	$cctpl -> set('modunheader',$modunheader);
	$cctpl -> set('tile',$page_tile);
	//$cctpl -> set('menuleft',$menuleft);
	// $cctpl -> set('userid',$userid);
	// $cctpl -> set('token',$token);
	$cctpl -> set('page_template_path',$page_template_path);

	//echo $page_content = $cctpl->fetch();		//echo tại file include
	$_content = $cctpl->fetch();				//echo tại file index.php
?>

