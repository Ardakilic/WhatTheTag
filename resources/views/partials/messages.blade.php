@if(count($errors) > 0)
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input.<br><br>
		<ul>
			@foreach ($errors->all() as $eachError)
				<li>{{ $eachError }}</li>
			@endforeach
		</ul>
	</div>
@endif

{{-- For Sessions --}}
@if(\Session::has('success'))
	<div class="alert alert-success">
		<strong>Success!</strong><br><br>
		{{ \Session::get('success') }}
	</div>
@endif
{{-- this conflicts with validation errors so we need to check --}}
@if(\Session::has('error'))
	<div class="alert alert-danger">
		<strong>Whoops!</strong> There were some problems with your input<br><br>
		{{ \Session::get('error') }}
	</div>
@endif
@if(\Session::has('warning'))
	<div class="alert alert-warning">
		<strong>Warning!</strong><br><br>
		{{ \Session::get('warning') }}
	</div>
@endif
@if(\Session::has('info'))
	<div class="alert alert-info">
		<strong>>Info:</strong><br><br>
		{{ \Session::get('warning') }}
	</div>
@endif

{{-- For Varaiables --}}
@if(isset($success))
	<div class="alert alert-success">
		<strong>Success!</strong><br><br>
		{{ $success }}
	</div>
@endif
@if(isset($error))
	<div class="alert alert-danger">
		<strong>>Whoops!</strong> There were some problems with your input<br><br>
		{{ $error }}
	</div>
@endif
@if(isset($warning))
	<div class="alert alert-warning">
		<strong>Warning!</strong><br><br>
		{{ $warning }}
	</div>
@endif
@if(isset($info))
	<div class="alert alert-warning">
		<strong>>Info</strong><br><br>
		{{ $info }}
	</div>
@endif