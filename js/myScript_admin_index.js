$(document).ready(function(){
	get_chart();
	get_chart2();
	get_chart3();
	get_chart4();
	get_chart5();
	get_chart6();
	today_status();
	last_login();
	$('.sync_notice').html('อัพเดทข้อมูลทุก 10 วินาที');
	//ตั้งให้ดึงข้อมูล today_status ทุกๆ 10 วิ
	today_status_set = setInterval(today_status, 10000);   // 1000 = 1 second

	$(document).on("change", ".year_select", function () {
		get_chart();
	});

	$(document).on("change", ".chart2_select", function () {
		get_chart2();
	});

	$(document).on("change", ".chart3_select", function () {
		get_chart3();
	});

	$(document).on("change", ".chart4_select, .chart4_select_source", function () {
		get_chart4();
	});

	$(document).on("change", ".chart5_select", function () {
		get_chart5();
	});

	$(document).on("change", ".chart6_select", function () {
		get_chart6();
	});

	$(document).on("click",".pause", function(e){
		e.preventDefault();
		clearInterval(today_status_set);
		$('.pause, .sync').prop('hidden',true);
		$('.play').prop('hidden',false);
		$('.sync_notice').html('หยุดการอัพเดทข้อมูล');
	});

	$(document).on("click",".play", function(e){
		e.preventDefault();
		today_status_set = setInterval(today_status, 10000);
		$('.play').prop('hidden',true);
		$('.pause, .sync').prop('hidden',false);
		$('.sync_notice').html('อัพเดทข้อมูลทุก 10 วินาที');
	});

});

//////today status///////
function today_status(){
	$('#today').html('<div id="loading"><h5><i class=\"fas fa-spinner fa-spin\"></i>Loading .....</h5></div>');
	$.ajax({
		url: "../system/search_cmd.php",
		type: "json",
		method: "POST",
		data:{
			command: "search_booklist_today"
		},
		success:function(data){
			var html;
			html = 	'<table class="table sm-font-admin" style="font-size: 14px; text-align:center;>'+
				    	'<thead>'+
				    		'<tr class="text-center">'+
				    			'<th>ประเภทห้อง</th>'+
				    			'<th>จำนวนห้องที่ต้องCheck out</th>'+
				    			'<th class="text-left">จำนวนห้องว่าง</th>'+
				    		'</tr>';

			$.each(JSON.parse(data),function(key, value){
				html += '<tr>'+
					    	'<td class="text-left">'+value.h_type_name+'</td>'+
					    	'<td class="text-center">'+value.summary_of_checkout+'</td>';
					    	if(value.summary_of_empty != 0){
					    		html+= '<td class="text-left text-success">';
					    	}
					    	else{
					    		html+= '<td class="text-left text-danger">';
					    	}
				html +=	    value.summary_of_empty;

							if(value.session > 0){
								html+= ' <span class="badge badge-pill badge-secondary"> '+value.session+' ห้องกำลังถูกเลือก <span class="spinner-grow text-light" role="status" style="width: 1rem; height: 1rem;"></span> </span>';
							}
				html +=		'</td>'+
					    '</tr>';
			});
			html += '</thead></table>';
			$('#today').html('');
			$(html).appendTo('#today');

		},
		error:function(){alert('error');}
	});
}

///Last login///
function last_login(){
	$('#last_login').html('<div id="loading"><h5><i class=\"fas fa-spinner fa-spin\"></i>Loading .....</h5></div>');
	$.ajax({
		url: "../system/search_cmd.php",
		type: "json",
		method: "POST",
		data:{
			command: "search_last_login"
		},
		success:function(data){
			var html;
			html = 	'<table class="table sm-font-admin" style="font-size: 14px; text-align:center;>'+
				    	'<thead>'+
				    		'<tr class="text-center">'+
				    			'<th>ชื่อบัญชี</th>'+
				    			'<th>ชื่อ</th>'+
				    			'<th>เข้าสู่ระบบล่าสุด</th>'+
				    		'</tr>';

			$.each(JSON.parse(data),function(key, value){
				html += '<tr>'+
					    	'<td class="text-left">'+value.h_user_username+'</td>'+
					    	'<td class="text-center">'+value.h_user_firstname+'</td>'+
					    	'<td class="text-center">'+value.h_user_login+'</td>'+
					    '</tr>';
			});
			html += '</thead></table>';
			$('#last_login').html('');
			$(html).appendTo('#last_login');

		},
		error:function(){alert('error');}
	});
}

function get_chart(){
	$.ajax({
		url: "../system/search_cmd.php",
		data:{
		    command: "total_of_month_chart",
		    year_select: $('.year_select').val()
		},
		type: "POST",
		success:function(data){
			data = JSON.parse(data);
			console.log(data);
			main_chart(data);
			$('#total').html(data.count_all);
			$('#cancel').html(data.count_cancel);
		},
		error:function (){ alert('error');}
	});
}

function get_chart2(){
	$.ajax({
		url: "../system/search_cmd.php",
		data:{
		    command: "total_of_Typeroom_chart",
		    year_select: $('.chart2_select').val()
		},
		type: "POST",
		success:function(data){
			data = JSON.parse(data);
			console.log(data);
			if(data.type_name === null){
				$('#myChart_doughnut').prop('hidden',true);
				$('.not_found').prop('hidden',false);
			}
			else{
				$('#myChart_doughnut').prop('hidden',false);
				$('.not_found').prop('hidden',true);
				doughnut_chart(data);
			}
		},
		error:function (){ alert('error');}
	});
}

function get_chart3(){
	$.ajax({
		url: "../system/search_cmd.php",
		data:{
		    command: "timerate_chart",
		    year_select: $('.chart3_select').val()
		},
		type: "POST",
		success:function(data){
			data = JSON.parse(data);
			console.log(data);
			if(data.time_all === null){
				$('#myChart_time').prop('hidden',true);
				$('.not_found3').prop('hidden',false);
			}
			else{
				$('#myChart_time').prop('hidden',false);
				$('.not_found3').prop('hidden',true);
				time_chart(data);
			}
		},
		error:function (){ alert('error');}
	});
}

function get_chart4(){
	$.ajax({
		url: "../system/search_cmd.php",
		data:{
		    command: "income_chart",
		    year_select: $('.chart4_select').val(),
		    source: $('.chart4_select_source').val()
		},
		type: "POST",
		success:function(data){
			data = JSON.parse(data);
			console.log(data);
			$('#total_income').html(data.count_income);
			$('.number').formatNumber();
			if(data.total_income === null){
				$('#myChart_income').prop('hidden',true);
				$('.not_found4').prop('hidden',false);
			}
			else{
				$('#myChart_income').prop('hidden',false);
				$('.not_found4').prop('hidden',true);
				income_chart(data);
			}
		},
		error:function (){ alert('error');}
	});
}

function get_chart5(){
	$.ajax({
		url: "../system/search_cmd.php",
		data:{
		    command: "paymenttype_chart",
		    year_select: $('.chart5_select').val(),
		},
		type: "POST",
		success:function(data){
			data = JSON.parse(data);
			console.log(data);

			if(data.type_name === null){
				$('#myChart_paymenttype').prop('hidden',true);
				$('.not_found').prop('hidden',false);
			}
			else{
				$('#myChart_paymenttype').prop('hidden',false);
				$('.not_found').prop('hidden',true);
				paymenttype_chart(data);
			}
		},
		error:function (){ alert('error');}
	});
}

function get_chart6(){
	$.ajax({
		url: "../system/search_cmd.php",
		data:{
		    command: "rate_chart",
		    year_select: $('.chart6_select').val(),
		},
		type: "POST",
		success:function(data){
			data = JSON.parse(data);
			console.log(data);

			if(data === null){
				$('#myChart_rate').prop('hidden',true);
				$('.not_found').prop('hidden',false);
			}
			else{
				$('#myChart_rate').prop('hidden',false);
				$('.not_found').prop('hidden',true);
				rate_chart(data);
			}
		},
		error:function (){ alert('error');}
	});
}

function main_chart(data){
	var ctx = document.getElementById('myChart').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'line',

	    // The data for our dataset
	    data: {
	        labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
	        datasets: [{
	            label: 'จำนวนรายการจองห้องพัก',
	            backgroundColor: 'rgb(255, 255, 255, 0)',
	            data: data.total_booking
	        },{
	        	label: 'จำนวนรายการที่ยกเลิก',
	            backgroundColor: 'rgb(255, 255, 255, 0)',
	            data: data.total_cancel
	        }]
	    },

	    // Configuration options go here
	    options: {
	    	plugins: {
	            colorschemes: {
	                scheme: 'tableau.ClassicMedium10'
	            }
	        },
	    	tooltips: {
	            callbacks: {
	                label: function(tooltipItems, data) {
	                    return data.datasets[tooltipItems.datasetIndex].label +': '+ data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] + ' รายการ';
	                }
	            }
        	}
	    }
	});
}

function doughnut_chart(data){
	var ctx2 = document.getElementById('myChart_doughnut').getContext('2d');
	var myDoughnutChart = new Chart(ctx2, {
	    type: 'doughnut',
	    data: {
				datasets: [{
					data: data.total_of_type
				}],
				labels: data.type_name,
			  },
		options: {
	        plugins: {
	            colorschemes: {
	                scheme: 'tableau.HueCircle19'
	            }
	        },
	        tooltips: {
	            callbacks: {
	                label: function(tooltipItems, data) {
	                    return data.labels[tooltipItems.index] +' ถูกจองไป: '+ data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] + ' ห้อง';
	                }
	            }
        	}
    	}
	});
}

function time_chart(data){
	var ctx3 = document.getElementById('myChart_time').getContext('2d');
	var chart = new Chart(ctx3, {
	    type: 'line',
	data: {
	labels: data.time_all,
	    datasets: [{
	      label: "จำนวนผู้ทำรายการจอง",
	      data: data.total_range,
	    }]
	  },
	  options: {
	    scales: {
	      xAxes:[{
	  type: 'time',
	  time: {
	    format: "HH:mm",
	    unit: 'hour',
	    unitStepSize: 1,
	    displayFormats: {
	      'minute': 'HH:mm',
	      'hour': 'HH:mm',
	      min: '00:00',
	      max: '23:59'
	    },
	}}],
	    },
	    plugins: {
            colorschemes: {
                scheme: 'tableau.HueCircle19'
            }
       	},
       	tooltips: {
            callbacks: {
                label: function(tooltipItems, data) {
                    return data.datasets[tooltipItems.datasetIndex].label +': '+ data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] + ' คน';
                }
            }
        }
	  }
	});
}

function income_chart(data){
	var ctx = document.getElementById('myChart_income').getContext('2d');
	var chart = new Chart(ctx, {
	    // The type of chart we want to create
	    type: 'line',

	    // The data for our dataset
	    data: {
	        labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
	        datasets: [{
	            label: 'จำนวนรายรับ',
	            data: data.total_income
	        }]
	    },

	    // Configuration options go here
	    options: {
	        plugins: {
	            colorschemes: {
	                scheme: 'tableau.HueCircle19'
	            }
	        },
	        tooltips: {
	            callbacks: {
	                label: function(tooltipItems, data) {
	                    return data.datasets[tooltipItems.datasetIndex].label +': '+ data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] + ' บาท';
	                }
	            }
        	}
    	}
	});
}

function paymenttype_chart(data){
	var ctx2 = document.getElementById('myChart_paymenttype').getContext('2d');
	var myDoughnutChart = new Chart(ctx2, {
	    type: 'doughnut',
	    data: {
				datasets: [{
					data: data.total_of_type
				}],
				labels: data.type_name,
			  },
		options: {
	        plugins: {
	            colorschemes: {
	                scheme: 'tableau.PurplePinkGray12'
	            }
	        },
	        tooltips: {
            callbacks: {
                label: function(tooltipItems, data) {
                    return data.labels[tooltipItems.index] +': '+ data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] + ' รายการ';
                }
            }

        }
    	}
	});
}

function rate_chart(data){
	var ctx2 = document.getElementById('myChart_rate').getContext('2d');
	var myBarChart = new Chart(ctx2, {
    type: 'bar',
    data: {
    			labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
				datasets: data
			  },
		options: {
	        plugins: {
	            colorschemes: {
	                scheme: 'tableau.PurplePinkGray12'
	            }
	        },
	        tooltips: {
            callbacks: {
                label: function(tooltipItems, data) {
                    return data.datasets[tooltipItems.datasetIndex].label+': '+ data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] + ' รายการ';
                }
            }

        }
    	}
	});
}