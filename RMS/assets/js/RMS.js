$(function(){
	$('.projectDescript').each(function(index, el) {				//處理project描述過長
		$(this).attr("title", '');
		var descriptLength = 150;
        if ($(this).text().length > descriptLength) {
            $(this).attr("title", $(this).text());
            var text = $(this).text().substring(0, descriptLength - 1) + "...";
            $(this).text(text);
        }
	});

	$('.projectName').each(function(index, el) {				//處理project Name過長
		$(this).attr("title", '');
		var descriptLength = 50;
        if ($(this).text().length > descriptLength) {
            $(this).attr("title", $(this).text());
            var text = $(this).text().substring(0, descriptLength - 1) + "...";
            $(this).text(text);
        }
	});
})