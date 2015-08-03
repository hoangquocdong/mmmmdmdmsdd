	//************************ set current meter/ sub for all page ******************************
	
	var unit=[]; num_point=[];
	var page1_meter = function(id_sub){

		$.ajax({
			url:api_link+'/page1_meter.php',
			type:"POST",
			data:{id_sub: id_sub},
			success:function(data){
				  $('.page1_meter').html('');
					if (data !=0){
					var meters = JSON.parse(data);
							var i= 0;
							$.each(meters, function(index, value){								
								$('.page1_meter').append('<li><div class="datas-text" id="meter'+i+'"><img src="img/meter.png" style="padding-right: 5px;"></img>'+ value['serial_meter']+'</div> </li>'); 
								unit[i]= value['unit_meter'];
								num_point[i]= value['count_point_meter'];
								i++;								
							});
								if (meter == null||meter == ''||meter == undefined) {
									meter = meters[0]['serial_meter'];
								}
								chart(meter);
							$(".page1_meter > li").click(function(){
								var url  = window.location.href;
								var urlarr = url.split("/");
								var curl = urlarr[urlarr.length-1];
								var newurl = url.split("&meter");
								newurl = newurl[0];
								window.location.href = newurl+'&meter='+$(this).text();
								chart(meter);
							});
				
				  } else {
					  
				  }
			 }, error: function(e){
				console.log('e '+e);
			}
		
		}); 
	}
	page1_meter(sub);
	
	//************************ End of Load first default meter information *****************************
	//************************ Function Apply Data to Instant Table ************************************	
	function apply_thongso(data){
		var i =1;
		for (var key in data){
			var ttt = '#last_instant .instant'+i;
			$(ttt).html(data[key]);
			i++;
		}
	}
	//************************ Function Apply Data to Current Table ************************************		
	function apply_chiso(data){
		var i =1;
		for (var key in data){
			var temp1 = '#last_current .current'+i;
			$(temp1).html(data[key]);
			i++;
		}
	}
	//************************Function  Draw Chart ***************** ***********************************	
	var drawchart = function(dt,$this){
		$this.highcharts({
			xAxis: {
			type: 'datetime',
						tickInterval: 3600 * 600, // one hour
						tickWidth: 0,
						gridLineWidth: 1,     
					},
					tooltip: {						
						shared: true,
						crosshairs: true,
						borderColor: '#058DC7',
						},
					series: [{
						name: 'Ia',
						data: dt[0]
						},
						{
							name: 'Ib',
							data: dt[1]
						},
						{
							name: 'Ic',
							data: dt[2]
						}
					]
		});	
	}
	//************************Function  Show Chart, Instant Table, Current Table ***********************************
	var chart = function(serial){

		dt =[], dt0 =[], dt1 =[];
		$.ajax({
				url:api_link+'/page1_meter_chart.php',
				type:"POST",
				data:{serial: serial},
				success:function(data){
					temp = JSON.parse(data);
					dt=temp.dongdien;
					drawchart(temp.dongdien,$('#container1'));
					drawchart(temp.dienap,$('#container2'));
					drawchart(temp.tanso,$('#container3'));		
					apply_thongso(temp.thongso);
					apply_chiso(temp.chiso);				
				}, error: function(e){
					console.log('e '+e);
				}
			
		}); 
	}