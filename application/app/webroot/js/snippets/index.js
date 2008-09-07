$(document).ready(function() {
	$('#load-snippets').click(function() {
		$.getJSON($.url('/snippets.json'), function(snippets) {
			$.each(snippets, function() {
				var $li = $('<li/>').text(this.Snippet.name+' ('+this.Snippet.description+')');
				$('#snippets').append($li);
			});
		});
		return false;
	});
});

$.url = function(url) {
	return $('base').attr('href')+url.substr(1);
};