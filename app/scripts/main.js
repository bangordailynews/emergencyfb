'use strict';
var posts = [];

function processData(data) {

	data.forEach(function(e) {
		
		var url = 'https://graph.facebook.com/' + e + '/posts?access_token=169032116512887|0dc1ca31cba2a38bece9150c3ddbb9d6';

		//Get posts from Facebook
		$.getJSON(url, function(json) {
			processPosts(json);
			console.log(posts);
		});
	});
}

function processPosts(json) {
	$.each(json, function(e) {
		// posts.push(this);
		if(this.type == 'status') {
			posts.push(this);
		}
	});
}


$(function() {
	$.ajax({
		url: '../facebookIDs.json',
		type: 'GET',
		dataType: 'json',
		data: {param1: 'value1'},
	})
	.done(function(data) {
		processData(data);
	})
	.fail(function() {
		console.log('error');
	})
	.always(function() {
		console.log('complete');
	});
	
});
