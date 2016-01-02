$(function (){
	$(".edit-btn").click(function(event) {
		$.ajax({
			url: $(this).data('url'),
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
				if(response.r_id != null ){
					var seqNum=0;
					for(var i = 0 ;i < $(".reqRow").length ;i++)
					{
						var currentRow = $(".reqRow")[i]; 
						if($(currentRow).attr('reqId') == response.r_id){
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
});