$(function()
{
	$.each($(".traceablity-cell"),function(index,cell){
		if(cell.innerHTML== 'O')
			$(cell).addClass("traceablity-Green");
		else if (cell.innerHTML== 'X')
			$(cell).addClass("traceablity-Red");
	})

/*	$(".traceability-header").click(function(event){
		$.ajax({
			url: $(this).attr('data-url'),
			type: 'get',
			success:function (response){
				$('#modal-body').html(response);
			}
		})		
	})*/
});