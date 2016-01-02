$(function (){
	$(".edit-btn").click(function(event) {
		$.ajax({
			url: $(this).attr('data-url'),
			type: 'get',
			success:function (response){
				$('#modal-body').html(response);
			}
		})
	});
	$(".remove-btn").click(function(event) {
		$.ajax({
			url: $(this).attr('data-url'),
			type: 'get',
			dataType: 'JSON',
			success:function (response){
				//修正#號碼
				if(response.t_id != null ){
					var seqNum=0;
					for(var i = 0 ;i < $(".testcaseRow").length ;i++)
					{
						var currentRow = $(".testcaseRow")[i]; 
						if($(currentRow).attr('testcaseid')==response.t_id){
							seqNum = $(currentRow).find('td')[0].innerHTML;
							$(currentRow).remove();
						}
					}
					for(var i = seqNum ;i < $(".seqNumber").length ;i++)	
						$(".seqNumber")[i].innerHTML = $(".seqNumber")[i].innerHTML-1;;		
					//修正號碼結束
				}
				//動作提醒
				$.smkAlert({
				    text: response.message,
				    type: response.messageType,
				    position: 'top-center'
				});
			}
		})
	});
	//搜尋的AJAX
	$(".btn-search").click(function(event) {
		$.ajax({
			url: '/RMS/index.php/testcasemanage/search',
			dataType:'JSON',
			type:'POST',
			data:
			{
				searchCondition: $('#searchCondition').val(),
			},
			success:function(response)
			{		
				for(var i = 0; i<  $(".testcaseRow").length ;i++)
				{
					var currentRow = $(".testcaseRow")[i];
					$(currentRow).hide();
				}		
				$.each(response,function(index,value){
					$.each($(".testcaseRow"),function(rowIndex,rowValue)
					{
						if($(rowValue).attr('testcaseid')==value['t_id'])
							$(rowValue).show();
					})
				})
			}

			
		})		
	});
});
