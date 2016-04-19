jQuery(window).load(function(){

	// For debug and do it on load add this
	jQuery('body').on('click', '#fpi-import', function(ev){
		ev.preventDefault();

		var facebook_import = new fpi_import();		
			connect 		= facebook_import.init();

		if( !connect ){
			console.log('you are not connected')
			return false;
		}
		

		fpi_page.list.innerHTML = Mustache.render( fpi_page.message, { message: 'Loading' });

		facebook_import.page_content({
			page_id: fpi.page_id,
			on_response: function(response){
				facebook_import.on_response(response);
			}
		});
		
	});

});

fpi_import.prototype.on_response = function(response){

  	var rendered = Mustache.render(fpi_page.template, response );
	Mustache.parse(fpi_page.template);   // optional, speeds up future uses

	fpi_page.list.innerHTML = rendered;

	fpi_post_list(response).success(function(data){
		
		console.log(data)		
	
	}).error(function(x){
		
		console.log(x.responseText)

	});

};