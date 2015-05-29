$('#admin-users-table').DataTable({
	processing: true,
	serverSide: true,
	ajax: $('#admin-users-table').attr('data-source'),
	columns: [
		{data: 'id', name: 'id'},
		{data: 'name', name: 'name'},
		{data: 'email', name: 'email'},
		{data: 'role', name: 'role'},
		{data: 'created_at', name: 'created_at'},
		{data: 'updated_at', name: 'updated_at'},
		{data: 'action', name: 'action', orderable: false, searchable: false}
	]
});