@extends('app')

@section('header_assets')
<link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" />
@stop

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			<div class="panel-heading clearfix">
						
				<div class="pull-left"><h5>List Users</h5></div>
				<div class="pull-right"><a class="btn btn-default" href="{{ url('/admin/users/new') }}">New User</a></div>
				
			</div>
				
				<div class="panel-body">
				
					@include('partials.messages')
					
					<table id="users-table" class="table table-condensed">
						<thead>
							<tr>
								<th>Id</th>
								<th>Name</th>
								<th>Email</th>
								<th>Role</th>
								<th>Created At</th>
								<th>Updated At</th>
								<th>Action</th>
							</tr>
						</thead>
					</table>
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer_assets')
<script type="text/javascript" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$('#users-table').DataTable({
	processing: true,
	serverSide: true,
	ajax: '{{ url('admin/users/grid') }}',
	columns: [
		{data: 'id', name: 'id'},
		{data: 'name', name: 'name'},
		{data: 'email', name: 'email'},
		{data: 'role', name: 'role'},
		{data: 'created_at', name: 'created_at'},
		{data: 'updated_at', name: 'updated_at'},
		{data: 'action', name: 'action', orderable: false, searchable: false}
	],
	fnInitComplete: function() {
		{{-- Fixes input and select box's styles according to bootstrap 3 --}}
		$('.dataTables_filter input').addClass('form-control').css({display:'inline-block', width:'152px'});
		$('.dataTables_length select').addClass('form-control').css({display:'inline-block', width:'60px'});
	}
});

$(document).on('click', '.delete-button', function(){
	return confirm('Are you sure you want to delete this user?');
});
</script>
@endsection