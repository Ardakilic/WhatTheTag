@extends('app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
			<div class="panel-heading clearfix">
				
				<div class="pull-left"><h5><strong>List Photos</strong></h5></div>
				<div class="pull-right"><a class="btn btn-default" href="{{ url('/photo/new') }}">New Photo</a></div>
				
			</div>
				
				<div class="panel-body">
				
					@include('partials.messages')
					
					<table id="admin-photos-table" class="table table-condensed" data-source="{{ url('admin/photos/grid') }}">
						<thead>
							<tr>
								<th>Id</th>
								<th>Image</th>
								<th>Title</th>
								<th>User Name</th>
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


{{-- Modal --}}
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalTitle">Modal title</h4>
			</div>
			<div class="modal-body">
				<img src=""class="img-responsive">
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-8">
						<span id="modalTagWrapper" class="pull-left"></span>
					</div>
					
					<div class="col-md-4">
						<a id="modalDownloadBtn" role="button" href="" class="btn btn-default" target="_blank">Download</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- /Modal --}}

@endsection