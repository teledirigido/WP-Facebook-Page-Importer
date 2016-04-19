// Facebook variables
var fpi = {
	app_id: 	document.getElementById('fpi_app_id').value,
	page_id: 	document.getElementById('fpi_page_id').value,
};

var fpi_page = {
	list: 		document.getElementById('fpi-list'),
	message: 	document.getElementById('facebook-post-message').innerHTML,
	template: 	document.getElementById('facebook-post-el').innerHTML
};

var fpi_post_list = function(list){

	// here is where the request will happen
	return jQuery.ajax({
		url:  ajaxurl,
		method: 'POST',
		async: true,
		cache: false,
		// dataType: 'JSON',
		data: {
			'action': 		'do_ajax',
			'fn': 			'do_fpi_ajax',
			'list': 		list,
		},

	});
};



// Facebook connect script
(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));


// Facebook init when fbAsyncInit load
window.fbAsyncInit = function() {

    FB.init({
      appId      : fpi.app_id,
      cookie	 : true,
      xfbml      : true,
      version    : 'v2.5'
    });

}

var fpi_import = function(){

	this.init = function(){

		FB.getLoginStatus(function(response) {

			if (response.status === 'connected') {
				return true;
			}

			else {
				FB.login();
				return false;
			}

		});

		return true;

	},

	this.page_content = function( options ){

		FB.api(
			options.page_id + '/feed', 'GET',
			{"fields":"link,full_picture,story,message,created_time,actions"},
			function(response) {
		      	// Insert your code here
				typeof this.on_response === 'function' && this.on_response(response);
		  	}.bind(this)
		);

	}

}
