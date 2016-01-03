$(function(){
	$('.edit-btn').click(function(event) {
		$.ajax({
            url: $(this).attr('data-url'),
            type: 'get',
            success: function(response) {
            	$('#modal-body').html(response);

            	$('#Addmember').click(function(event) {                //加入成員
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
            				
                            if (response.projectPriority == 2)
                            {
                                var $name =  $('<a>').addClass('btn btn-primary btn-xs')
                                                     .attr('data-priority', response.projectPriority)
                                                     .attr('id', 'btnu_id-'+response.userID);
                            }else if (response.projectPriority == 1)
                            {
                                var $name =  $('<a>').addClass('btn btn-info btn-xs')
                                                     .attr('data-priority', response.projectPriority)
                                                     .attr('id', 'btnu_id-'+response.userID); 
                            }else
                            {
                                var $name = $('<a>').addClass('btn btn-default btn-xs')
                                                    .attr('data-priority', response.projectPriority)
                                                    .attr('id', 'btnu_id-'+response.userID);
                            }
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
                            $('#opt-'+response.userID).remove();     //加入後刪除選項
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
                                    $('#u_id-'+response.u_id).remove();
                                })
                            });
            			}
            		})            		
            	});

                $('.member').click(function(event) {
                    var u_id = $(this).attr('data-uId');
                    var priority = $(this).attr('data-priority');
                    $.ajax({
                        url: '/RMS/index.php/projectmanage/changePriority/' + u_id + '/' + p_id + '/' + priority,
                        dataType: 'JSON',
                        data: {
                            u_id: u_id,
                            p_id: p_id,
                            priority : priority,
                        },
                    })
                    .success(function(response) {
                        $('#btnu_id-'+u_id).attr('data-priority',response)
                        if (response == 0)
                        {
                            $('#a-uid-'+u_id).removeClass('btn-success');
                            $('#a-uid-'+u_id).addClass('btn-dafault');
                        }
                        else if (response == 1)
                        {
                            $('#a-uid-'+u_id).removeClass('btn-dafault');
                            $('#a-uid-'+u_id).addClass('btn-info');
                        }
                        else
                        {
                            $('#a-uid-'+u_id).removeClass('btn-info');
                            $('#a-uid-'+u_id).addClass('btn-success');
                        }
                        // var $option = $('<option>')
                        //                     .attr('id','opt-'+response.u_id)
                        //                     .attr('value',response.u_id)
                        //                     .html(response.u_id+ ', ' + response.name);
                        // $option.appendTo($('#newMember'));
                        // $('#u_id-' + response.u_id).remove();       //刪除後加入選項
                    })
                });
                
                $('.removeMember').click(function(event) {              //刪除成員
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
                        $('#u_id-' + response.u_id).remove();       //刪除後加入選項
                    })
                });
            }
        });
	});
    
    //置入提示視窗資料
    $('.project').click(function(event) {
        var p_id = $(this).attr('data-proID');
        $.ajax({
            url: '/RMS/index.php/projectmanage/getProjectData/' + p_id,
            dataType: 'JSON',
            data: {p_id: p_id},
        })
        .success(function(response) {
            var membersName='';
            console.log(response);
            //console.log(response[0].userName);
            for (var i in response){
                membersName = membersName + response[i].userName + ', ';
                console.log(response[i].userName);
            }
            console.log('#pro-'+p_id);
            $('#pro-'+p_id).attr('data-content',"建立日期：project.date <br /> 成員： <br />" + membersName);
        }) 
    });

    // 點擊跳提示視窗
    $('.project').popover({
        html: true
    });

})