/**
 * Common Scripts
 */

/**
 * Listing JavaScripts
 */

$("[rel='tooltip']").tooltip();

//http://bootsnipp.com/snippets/9jA
$('.list-thumbnail').hover(
	function(){
		$(this).find('.list-caption').slideDown(250); //.fadeIn(250)
	},
	function(){
		$(this).find('.list-caption').slideUp(250); //.fadeOut(205)
	}
);

//To fill the modal directly from content
$('a[data-link-type="list-modal"]').click(function() {
	$('#myModal #myModalTitle').text($(this).attr('data-img-title'));
	$('#myModal img').attr('src', $(this).attr('data-img-url'));
	$('#myModal #modalDownloadBtn').attr('href', $(this).attr('data-img-url'));
	// fetch the tags from container and add it to left footer of modal
	$('#myModal #modalTagWrapper').html('<h4>' + $(this).closest('.caption').find('.tagsWrapper').html() + '<h4>');
});


/**
 * Forms and Lists related common JavaScripts
 */

//Datatables should throw errors to the console instead of alert()
$.fn.dataTableExt.sErrMode = 'throw';

//For both front-end and backend forms
//Taken from http://stackoverflow.com/a/4459419/570763
function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		
		reader.onload = function (e) {
			$('#previewGrp').removeClass('hide');
			$('#previewImg').attr('src', e.target.result);
		};
		
		reader.readAsDataURL(input.files[0]);
	}
}

//Note: maybe in future this may change
$('input[name="photo"]').change(function() {
	readURL(this);
});

//For delete confirmation
$(document).on('click', '.delete-button', function(){
	return confirm('Are you sure you want to delete this photo?');
});