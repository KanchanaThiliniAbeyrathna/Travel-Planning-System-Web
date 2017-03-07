$(document).ready(function () {
	var left = $('#box').position().left;
	var top = $('#box').position().top;
	var width = $('#box').width;

	$('#search_result').css('left',left).css('top',top+37).css('width',width);

	$('#search_box').keyup(function(){
		var value = $(this).val();

		if( value != ''){
			$.post('search.php', {value:value} , function(data){
				$('#search_result').html(data);
			});
		}

	});
});