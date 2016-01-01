$(function(){
	$('.edit-btn').click(function(event) {
		$.ajax({
            url: $(this).attr('data-url'),
            type: 'get',
            success: function(response) {
            	$('#modal-body').html(response);           // 浮框部分
            }
        });
	});
