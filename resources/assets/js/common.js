/**
 * Common Scripts
 */

/**
 * Listing JavaScripts
 */

// Wrapped as a function because after each search this should be re-triggered
function triggerTooltips() {
    $("[rel='tooltip']").tooltip();

    //http://bootsnipp.com/snippets/9jA
    $('.list-thumbnail').hover(
        function () {
            $(this).find('.list-caption').slideDown(250); //.fadeIn(250)
        },
        function () {
            $(this).find('.list-caption').slideUp(250); //.fadeOut(205)
        }
    );

    //To fill the modal directly from content
    $('a[data-link-type="list-modal"]').click(function () {
        $('#myModal #myModalTitle').text($(this).attr('data-img-title'));
        $('#myModal img').attr('src', $(this).attr('data-img-url'));
        $('#myModal #modalDownloadBtn').attr('href', $(this).attr('data-img-url'));
        // fetch the tags from container and add it to left footer of modal
        $('#myModal #modalTagWrapper').html('<h4>' + $(this).closest('.caption').find('.tagsWrapper').html() + '<h4>');
    });

    console.log('Tooltip triggered!');
}

triggerTooltips();


/**
 * Forms and Lists related common JavaScripts
 */

//Datatables should throw errors to the console instead of alert()
$.fn.dataTableExt.sErrMode = 'throw';

//For delete confirmation
$(document).on('click', '.delete-button', function () {
    return confirm('Are you sure you want to delete this photo?');
});

//Dropify Plugin for image uploads
$('.dropify').dropify();