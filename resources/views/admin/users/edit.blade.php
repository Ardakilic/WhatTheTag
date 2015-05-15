@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Editing the user: {{$user->name}}</div>
				<div class="panel-body">
					
					@include('partials.messages')

					<form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/users/edit/'.$user->id) }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Name</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail Address</label>
							<div class="col-md-6">
								<input disabled="disabled" type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Role</label>
							<div class="col-md-6">
								<select name="role" class="form-control">
									<option value="user"{{ old('role', $user->role) == 'user' ? 'selected="selected"' : '' }}>User</option>
									<option value="admin"{{ old('role', $user->role) == 'admin' ? 'selected="selected"' : '' }}>Admin</option>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">New Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirm Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Update
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection