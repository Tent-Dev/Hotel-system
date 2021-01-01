

var thispage_is;

function new_pagination(data,page,isnull){
	var html_pagination = 	'<ul class="pagination_btn pagination pagination-sm">';
								if(page != 1){
									html_pagination += '<li class="page-item PreeValue"><a href="#" class="page-link"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>';
								}
								for(var j=0; j<data.total_page;j++){
									var num = (j+1);
									var active = '';
									if(page == num){
										active = 'active';
									}
									//limit_to_page = data.perpage*j;
									html_pagination +=	'<li class="page-item '+active+'"><a href="#" class="to_page page-link" data-page="'+num+'">'+num+'</a></li>';
								}
								if(page != data.total_page){
									html_pagination += '<li class="page-item nextValue"><a href="#" class="page-link"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>';
								}
	html_pagination +=		'</ul>';

	if(thispage_is == undefined){
		thispage_is = 1;
	}
	$('.pagination-div').html(html_pagination);
	$('.sumresult').html('&nbsp;'+data.count);

	$('.to_page').click(function(e){
		e.preventDefault();
		var current_page = $(this).data('page');
		thispage_is = $(this).data('page');
		$('#display_room').empty();
		get_data(current_page);
	});

	$('.nextValue').click(function(e){
		e.preventDefault();
		var num_next_page = thispage_is;
		++num_next_page;
		if(data.total_page>=(num_next_page)) {
			console.log('Next btn: '+num_next_page+' / '+data.total_page);
			$('#display_room').empty();
			get_data(++thispage_is);
		}
	});

	$('.PreeValue').click(function(e){
  		e.preventDefault();
  		var num_preveious_page = thispage_is;
  		--num_preveious_page;
  		if(num_preveious_page != 0) {
  			console.log('Previous btn: '+num_preveious_page+' / '+thispage_is);
			$('#display_room').empty();
			get_data(--thispage_is);
		}
	});

	//เช็คหลังลบข้อมูลถ้าหน้านั้นไม่มีข้อมูลแสดงแล้ว
	if(isnull === true){
			console.log('Current page is: '+thispage_is+'\nMove to last page:'+(--thispage_is));
			if(thispage_is > 0){
				get_data(thispage_is);
			}
			else{
				$('#display_bed').html('<br>No bed found.');
				$('.PreeValue, .nextValue').prop('hidden',true);
				$('.sumresult').html('&nbsp;0');
			}
	}

}

function checkbox_save(){
	try{
		$.each(checkbox_get_global,function(key, value){
	    	$('.checkbox-delete[value="'+value+'"]').prop('checked',true);
		});
		$('#selectDeleteMorebtn').val(quote+' '+checkbox_get_global.length+' รายการ');
		console.log('Recent_checkbox all\n',checkbox_get_global);
	}
	catch(e){}
}