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
});