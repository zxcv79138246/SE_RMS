$(function(){
	$('.edit-btn').click(function(event) {
		$.ajax({
            url: $(this).attr('data-url'),
            type: 'get',
            success: function(response) {
            	$('#modal-body').html(response);

            	$('#Addmember').click(function(event) {                //加入成員
            		$.ajax({
            			url: '/RMS/index.php/reviewrequirement/add_reviewer',
            			type: 'POST',
            			dataType: 'JSON',
            			data: {
            				u_id: $('#newMember').val(),
            				r_id: r_id,
            				p_id: p_id
            			},
            		})
            		.success(function(response) {
            			if (response)
            			{
                            var $span = $('<span>')
                                            .attr('id','u_id-'+response.u_id);
            				var $name = $('<a>')
            								.addClass('btn btn-default btn-xs')
                            var $data = $('<span>')
                                            .addClass('member')
                                            .attr('data-uId', response.u_id)
                                            .html(response.u_id + ', ' + response.name);
            				var $remove = $('<a>')
    										.addClass('btn btn-danger btn-xs removeMember')
    										.html('x')
    										.attr('data-uId', response.u_id);
                            $name.append($data);
    						$span.append($name).append($remove);
    						$span.appendTo($('.member-list'));
                            $('#opt-'+response.u_id).remove();     //加入後刪除選項
                            $remove.click(function(event) {
                                var u_id = $(this).attr('data-uId');
                                $.ajax({
                                    url: '/RMS/index.php/reviewrequirement/remove_reviewer/' + u_id + '/' + r_id,
                                    dataType: 'JSON',
                                    data: {
                                        u_id: u_id,
                                        r_id: r_id
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
                
                $('.removeMember').click(function(event) {              //刪除成員
                    var u_id = $(this).attr('data-uId');
                    $.ajax({
                        url: '/RMS/index.php/reviewrequirement/remove_reviewer/' + u_id + '/' + r_id,
                        dataType: 'JSON',
                        data: {
                            u_id: u_id,
                            r_id: r_id,
                        },
                    })
                    .success(function(response) {
                        var $option = $('<option>')
                                            .attr('id','opt-'+response.u_id)
                                            .attr('value',response.u_id)
                                            .html(response.u_id+ ', ' + response.name);
                        $option.appendTo($('#newMember'));
                        $('#u_id-' + response.u_id).remove();       //刪除後加入選項
                    })
                });
            }
        });
	});
})