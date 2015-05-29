$(document).on('click', 'a[data-modal-type="admin-modal"]', function() {
	$('#myModal #myModalTitle').text($(this).attr('data-img-title'));
	$('#myModal img').attr('src', $(this).attr('data-img-url'));
	$('#myModal #modalDownloadBtn').attr('href', $(this).attr('data-img-url'));
});


function triggerTooltip() {
	$('img[data-toggle="tooltip"]').tooltip();
}

$('#admin-photos-table')
	.on('order.dt',	function () { triggerTooltip(); } )
	.on('search.dt',function () { triggerTooltip(); } )
	.on('page.dt',	function () { triggerTooltip(); } )
	.DataTable({
		processing: true,
		serverSide: true,
		ajax: $('#admin-photos-table').attr('data-source'), //$(this) is not working
		columns: [
			{data: 'id'},
			{data: 'image', orderable: false, searchable: false},
			{data: 'title'},
			{data: 'name'},
			{data: 'created_at'},
			{data: 'updated_at'},
			{data: 'action', name: 'action', orderable: false, searchable: false}
		],
		fnInitComplete: function() {
			triggerTooltip();
		}
	});
