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
	$('#searchTarget').change(function(event){
		console.log('Click opt');
		$('#opt_display_1').attr('lable', 'Functional');
		$('#opt_display_2').attr('lable', 'Non-functional');
	});
	$(".btn-search").click(function(event) {
		$.ajax({
			url: '/RMS/index.php/requirementmanage/search',
			dataType:'JSON',
			type:'POST',
			data:
			{
				searchCondition: $('#searchCondition').val(),
				searchTarget_1: $('#searchTarget_1').val(),
				searchTarget_2: $('#searchTarget_2').val(),
			},
			success:function(response)
			{	
				console.log(response);
				for(var i = 0; i<  $(".reqRow").length ;i++)
				{
					var currentRow = $(".reqRow")[i];
					$(currentRow).hide();
				}		
				$.each(response,function(index,value){
					$.each($(".reqRow"),function(rowIndex,rowValue)
					{
						if($(rowValue).attr('reqId')==value['r_id']&& $('#searchTarget').val()=='name')
							$(rowValue).show();
						if($(rowValue).attr('functional')==value['functional'] && $('#searchTarget').val()=='functional')
							$(rowValue).show();
						if($(rowValue).attr('state')==value['state'] && $('#searchTarget').val()=='state')
							$(rowValue).show();
						if($(rowValue).attr('owner')==value['owner'] && $('#searchTarget').val()=='owner')
							$(rowValue).show();
					})
				})
			}
		})		
	});
});