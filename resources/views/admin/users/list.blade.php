@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			<div class="panel-heading clearfix">
				
				<div class="pull-left"><h5><strong>List Users</strong></h5></div>
				<div class="pull-right"><a class="btn btn-default" href="{{ url('/admin/users/new') }}">New User</a></div>
				
			</div>
				
				<div class="panel-body">
				
					@include('partials.messages')
					
					<table id="admin-users-table" class="table table-condensed" data-source="{{ url('admin/users/grid') }}">
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