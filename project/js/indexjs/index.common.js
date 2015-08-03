// Check User permission 
		var checktoken = <?php echo $checktoken ?>;
		userid = <?php echo $userid; ?>;
		token ="<?php echo $token; ?>";
		if (checktoken!=1) { alert('Tài khoản của bạn đã đăng nhập ở thiết bị khác, bạn hãy đăng nhập lại để tiếp tục!'); window.location = "index.php?page=login";}
		var underheadpage = '<div class="bread-crumb pull-right"><a href="index.php?page=doxa"><i class="fa fa-home"></i> Home</a><span class="divider">/</span><a href="index.php?page=doxa" class="bread-current">Dashboard</a></div><div class="clearfix"></div>';
		// Load selection Page    
		$('.navbar-nav a').click(function(){
		var path = '';
		if ($(this).hasClass('manager_instant_meter')){ 
			path = "<?php echo $page_template_path; ?>/manager_instant_meter.html";
		} else if ($(this).hasClass('manager_profile_meter')){ 
			path = "<?php echo $page_template_path; ?>/manager_profile_meter.html";
		} else if ($(this).hasClass('manager_current_meter')){ 
			path = "<?php echo $page_template_path; ?>/manager_current_meter.html";
		} else if ($(this).hasClass('manager_h1_meter')){ 
			path = "<?php echo $page_template_path; ?>/manager_h1_meter.html";
		} else if ($(this).hasClass('manager_sum_h1_meter')){ 
			path = "<?php echo $page_template_path; ?>/manager_sum_h1_meter.html";
		} else if ($(this).hasClass('manager_price')){ 
			path = "<?php echo $page_template_path; ?>/manager_price.html";
		} else if ($(this).hasClass('manager_dataipp')){ 
			path = "<?php echo $page_template_path; ?>/manager_dataipp.html";
			$('.page-head').html('<h2 class="pull-left"><i class="fa fa-table"></i> Quản lý thông tin nhà máy</h2>'+underheadpage);
		} else if ($(this).hasClass('manager_equipmentipp')){ 
			path = "<?php echo $page_template_path; ?>/manager_equipmentipp.html";
			$('.page-head').html('<h2 class="pull-left"><i class="fa fa-table"></i> Quản lý thiết bị đo lường</h2>'+underheadpage);
		} else if ($(this).hasClass('manager_fileipp')){ 
			path = "<?php echo $page_template_path; ?>/manager_fileipp.html";
			$('.page-head').html('<h2 class="pull-left"><i class="fa fa-table"></i> Quản lý hồ sơ nhà máy</h2>'+underheadpage);
		}
		// Hide and Show menuleft
		if (path != ''){ 
		if ($(this).hasClass('doxa')){
		  $('.sidebar').css('visibility','visible');
		  $('.mainbar').css('margin-left','230px');
		} else if ($(this).hasClass('qlnm')) {
		  $('.sidebar').css('visibility','hidden');
		  $('.mainbar').css('margin-left','0px');
		}
		$('.matter').load(path); 
		}

      });
		//************************ Alert Log   **********************************************
		$('.doxa .thongbao').click(function(){
		  alert('Log nha may online offline hoac mot canh bao gi do');
		});
		//************************ Generate menu left  *****************************************
		var menu_left = <?php echo $menuleft; ?>;
		var firstsub = menu_left[0][1][0]['id_sub'];
        $.each(menu_left, function(index, value){
			var sub = value[1];
			var html = '<ul class="list_sub">';
			$.each(sub, function(index, val){
				html +='<li data-id="'+val["id_sub"] +'"><a >'+val["name_sub"]+'</a></li>';
			});
			html +='</ul>';
			$('#nav').append('<li class="has_sub">'+
					'<a><i class="fa fa-list-alt"></i>'+value[0]["name_pwc"]+'<span class="pull-right"><i class="fa fa-chevron-right"></i></span></a>'+html+       
				  '</li>'); 
        });
        //************************ Generate menu left  *****************************************
		//************************ Click menu left Item *****************************************
        $(".list_sub > li").click(function(){
          //alert($(this).data('id'));
          page1_meter($(this).data('id'));
          $('.name_sub_head').html($(this).html());
          
        });
		//************************ Click menu left Item *****************************************
		//************************ Click toggle menu left*****************************************
        $(".has_sub > a").click(function(e){
          e.preventDefault();
          var menu_li = $(this).parent("li");
          var menu_ul = $(this).next("ul");

          if(menu_li.hasClass("open")){
            menu_ul.slideUp(350);
            menu_li.removeClass("open")
          }
          else{
            $("#nav > li > ul").slideUp(350);
            $("#nav > li").removeClass("open");
            menu_ul.slideDown(350);
            menu_li.addClass("open");
          }
          });
