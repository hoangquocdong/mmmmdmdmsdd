<?
//require('donghq.php');
//require('libs/db_functions.php');

/*Lấy dữ liệu nhét vào bảng*/	
	session_start();
	$username = $_SESSION['memberloggedin'];
	$userid = $_SESSION['memberid']; 
	$user_right = $_SESSION['user_right']; 
	$_SESSION['page1']='home';

	CONNECT_DB();
		mysql_query("SET NAMES utf8");
		
		$tbl_name="nha_may"; // Table name 
		
		//$sql="SELECT * FROM $tbl_name ORDER BY id DESC LIMIT $first_topic , $limit_topics_per_page";
		//$result=mysql_query($sql);
		//while($rows=mysql_fetch_array($result)){
		//	$data[]=$rows;
		//}
		/*
		if ($userid==0){
			$sql="SELECT * FROM $tbl_name WHERE id_dienluc = 1";//  ORDER BY view DESC => most read
			$result=mysql_query($sql);

			$data1= array();
			while($rows=mysql_fetch_array($result)){
				$data1[]=$rows;
			}

			$sql="SELECT * FROM $tbl_name WHERE id_dienluc = 2";//  ORDER BY view DESC => most read
			$result=mysql_query($sql);

			$data2= array();
			while($rows=mysql_fetch_array($result)){
				$data2[]=$rows;
			}
		}
		*/

		$tbl_name1="dien_luc"; // Table name 

			$sql="SELECT * FROM $tbl_name1";// WHERE tbl_name1 = 1";//  ORDER BY view DESC => most read
			$result=mysql_query($sql);

			$dienluc= array();
			while($rows=mysql_fetch_array($result)){
				$dienluc[]=$rows;
			}
		//echo '<pre>';//print_r($dienluc);//echo '</pre>';

		$thuydien= array();
		$count_dienluc=0;
		foreach ($dienluc as $dienlucs) {
			
			$sql="SELECT * FROM $tbl_name WHERE id_dienluc = $count_dienluc";//  ORDER BY view DESC => most read
			$result=mysql_query($sql);

			//$data1= array();
			if ($result){
			while($rows=mysql_fetch_array($result)){
				$thuydien[$count_dienluc][]=$rows;
			}	
		}else {
			echo 'sql error'; exit();
		}

		$count_dienluc++;
		}
		//echo '<pre>';//print_r($thuydien);//echo '</pre>';
		//exit();
		
		//mysql_close();
		//$mtpl = new Template($modun_template_path.'/default.html');
		//$mtpl -> set('path',$modun_template_path);
		//$mtpl -> set('titlelist',$data);
		//$modun_content = $mtpl->fetch();

/*Hết lấy dữ liệu nhét vào bảng*/	

	//session_start();
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
	$page_tile = 'EVN Monitoring Program Homepage!';       			//Title của trang đó
	$header=load_modun('header');					//truyền các biến cần thiết cho template
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
	$cctpl -> set('header',$header);
	$cctpl -> set('dienluc',$dienluc);
	$cctpl -> set('thuydien',$thuydien);
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

