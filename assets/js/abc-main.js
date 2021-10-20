jQuery( function($){
	// show hide the hook flotting window
	$('body').on('click', '.abc-show-hide', function(e){

		e.preventDefault();
		var text = $('.abc-show-hide-text').text();
		if( ( 'Hide' == text ) || ( 'HideHide' == text ) ){
			$('.abc-nested-hooks-block').addClass('abc-hide');
			$('.abc-show-hide-text').text('Show');
		}else{
			$('.abc-nested-hooks-block').removeClass('abc-hide');
			$('.abc-show-hide-text').text('Hide');
		}
	})
});