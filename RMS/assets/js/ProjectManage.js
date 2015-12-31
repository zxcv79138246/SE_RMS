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
            				u_id: $('#addNewMember').val(),
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
    										.attr('data-id', response.projectID);
                            $name.append($data);
    						$span.append($name).append($remove);
    						$span.appendTo($('.member-list'));
                            $remove.click(function(event) {
                                var u_id = $('.member').attr('data-uId');
                                $.ajax({
                                    url: '/RMS/index.php/projectmanage/removeMember/' + u_id + '/' + p_id,
                                    dataType: 'JSON',
                                    data: {
                                        u_id: u_id,
                                        p_id: p_id,
                                    },
                                })
                                .success(function(response) {
                                    console.log(response);
                                    console.log(response.u_id);
                                    $('#u_id-' + response.u_id).remove();
                                    console.log($('#u_id-' + response.u_id));
                                })
                            });
            			}
            		})            		
            	});
                
                $('.removeMember').click(function(event) {
                    var u_id = $('.member').attr('data-uId');
                    $.ajax({
                        url: '/RMS/index.php/projectmanage/removeMember/' + u_id + '/' + p_id,
                        dataType: 'JSON',
                        data: {
                            u_id: u_id,
                            p_id: p_id,
                        },
                    })
                    .success(function(response) {
                        console.log(response);
                        console.log(response.u_id);
                        $('#u_id-' + response.u_id).remove();
                        console.log($('#u_id-' + response.u_id));
                    })
                });
            }
        });
	});
})