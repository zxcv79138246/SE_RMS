$(function (){
	$.each($(".testcaseRow"),function(index,row){
		if($(row).attr('needValidate')==1)
			$(row).addClass('needValidate');
	})

	$(".edit-btn").click(function(event) {
		$.ajax({
			url: $(this).attr('data-url'),
			type: 'get',
			success:function (response){
				$('#modal-body').html(response);
				$.each($(".relation"),function(index,element){
					if($(element).attr('needValidate') == 1){
						$(element).addClass('needValidate');
						$(element).addClass('Validate');
					}
				})
				$('.Validate').click(function(event){
					$.ajax({
						url: '/RMS/index.php/relation/validateRTRelation/',
						type:'POST',
						dataType:'JSON',
						data:
						{
							t_id: $('#testcaseName').attr('data-tid'),
							r_id: $(this).attr('data-rid'),
						},
						success: function(response){
						}
					})					
					$(this).removeClass('Validate');
					$(this).removeClass('needValidate');
					$(this).attr('needValidate',0);
					$.ajax({
						url: '/RMS/index.php/testcasemanage/needValidate/',
						type:'POST',
						dataType:'JSON',
						data:
						{
							t_id: $('#testcaseName').attr('data-tid'),
						},
						success: function(response)
						{
							console.log(response);
							if(response.needValidate==false)
								$.each($(".testcaseRow"),function(index,row){
									if($(row).attr('testcaseID')== $('#testcaseName').attr('data-tid')){
										$(row).attr('needValidate',0);
										$(row).removeClass('needValidate');
									}
								})
						}
					})					
				})
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
				searchTarget: $('#searchTarget').val(),
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
						if($(rowValue).attr('testcaseid')==value['t_id']&& $('#searchTarget').val()=='name')
							$(rowValue).show();
						if($(rowValue).attr('owner')==value['owner'] && $('#searchTarget').val()=='owner')
							$(rowValue).show();
					})
				})
			}			
		})		
	});
});
