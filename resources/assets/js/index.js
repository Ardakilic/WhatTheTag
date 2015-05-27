/**
 * Index JavaScripts
 */
 
//http://bootsnipp.com/snippets/9jA
$('.index-thumbnail').hover(
	function(){
		$(this).find('.index-caption').slideDown(250); //.fadeIn(250)
	},
	function(){
		$(this).find('.index-caption').slideUp(250); //.fadeOut(205)
	}
);



$('a[data-link-type="index-modal"]').click(function() {
	$('#myModal #myModalTitle').text($(this).attr('data-img-title'));
	$('#myModal img').attr('src', $(this).attr('data-img-url'));
	$('#myModal #modalDownloadBtn').attr('href', $(this).attr('data-img-url'));
	// fetch the tags from container and add it to left footer of modal
	$('#myModal #modalTagWrapper').html('<h4>' + $(this).closest('.caption').find('.tagsWrapper').html() + '<h4>');
});