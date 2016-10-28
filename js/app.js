$( document ).ready(function() {

	var compiled = _.template(
		'<% _.each(sentences, function(sentence, index, list) { %>' +
		'	<span class="<%= sentence[1] %>" data-old="<%= sentence[2] %>" data-new="<%= sentence[0] %>"><%= sentence[0] %>.</span>' +
		'<% }); %>'
	);

	function render(data) {
		data = data.map(function(sentence) {
			return [sentence[0], sentence[1], sentence[2]];
		});
		$('.template').html(compiled({ sentences : data}));
	}

	$( "body" ).on( "mouseover", ".changed", function(ev) {
		$(ev.target).html($(ev.target).data('old'));
	});

	$( "body" ).on( "mouseout", ".changed", function(ev) {
		$(ev.target).html($(ev.target).data('new'));
	});

	function query(text1, text2) {
		$.ajax({
			type: "POST",
			url: "api.php",
			data: {
				text1: text1,
				text2: text2
			},
			success: render,
			dataType: "json"
		})
	}


	$('#text1').keyup(function(ev) {
		query($(ev.target).val(), $('#text2').val());
	});

	$('#text2').keyup(function(ev) {
		query($('#text1').val(), $(ev.target).val());
	});
});
