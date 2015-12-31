$(function(){
	$('.edit-btn').click(function(event) {
		$.ajax({
            url: $(this).attr('data-url'),
            type: 'get',
            success: function(response) {
            	$('#modal-body').html(response);

            	$('#Addmember').click(function(event) {
            		$.ajax({
            			url: '/RMS/index.php/projectmanage/addMember',
            			type: 'POST',
            			dataType: 'JSON',
            			data: {
            				u_id: $('#newMember').val(),
            				p_id: p_id,
            				priority: $('#priority').val()
            			},
            		})
            		.success(function(response) {
            			if (response)
            			{
                            var $span = $('<span>')
                                            .attr('id','u_id-'+response.userID);
            				var $name = $('<a>')
            								.addClass('btn btn-default btn-xs')
                            var $data = $('<span>')
                                            .addClass('member')
                                            .attr('data-uId', response.userID)
                                            .html(response.userID + ', ' + response.userName);
            				var $remove = $('<a>')
    										.addClass('btn btn-danger btn-xs removeMember')
    										.html('x')
    										.attr('data-uId', response.userID);
                            $name.append($data);
    						$span.append($name).append($remove);
    						$span.appendTo($('.member-list'));
                            $('#opt-'+response.userID).remove();
                            $remove.click(function(event) {
                                var u_id = $(this).attr('data-uId');
                                $.ajax({
                                    url: '/RMS/index.php/projectmanage/removeMember/' + u_id + '/' + p_id,
                                    dataType: 'JSON',
                                    data: {
                                        u_id: u_id,
                                        p_id: p_id,
                                    },
                                })
                                .success(function(response) {
                                    var $option = $('<option>')
                                                        .attr('id','opt-'+response.u_id)
                                                        .attr('value',response.u_id)
                                                        .html(response.u_id+ ', ' + response.name);
                                    $option.appendTo($('#newMember'));
                                    $('#u_id-' + response.u_id).remove();
                                })
                            });
            			}
            		})            		
            	});
                
                $('.removeMember').click(function(event) {
                    var u_id = $(this).attr('data-uId');
                    $.ajax({
                        url: '/RMS/index.php/projectmanage/removeMember/' + u_id + '/' + p_id,
                        dataType: 'JSON',
                        data: {
                            u_id: u_id,
                            p_id: p_id,
                        },
                    })
                    .success(function(response) {
                        var $option = $('<option>')
                                            .attr('id','opt-'+response.u_id)
                                            .attr('value',response.u_id)
                                            .html(response.u_id+ ', ' + response.name);
                        $option.appendTo($('#newMember'));
                        $('#u_id-' + response.u_id).remove();
                    })
                });
            }
        });
	});
})