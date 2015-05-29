@extends('app')


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Editing the photo: {{ $photo->title }}</div>
				<div class="panel-body">
					
					@include('partials.messages')

					<form enctype="multipart/form-data" class="form-horizontal" role="form" method="POST" action="{{ url('/admin/photos/edit/'.$photo->id) }}">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						<div class="form-group">
							<label class="col-md-4 control-label">Title</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="title" value="{{ old('title', $photo->title) }}">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Owner</label>
							<div class="col-md-6">
								
								<select class="form-control" name="user_id">
									@foreach($users as $id => $name)
										<option value="{{ $id }}"{!! old('user_id', $photo->user_id) == $id ? ' selected="selected"' : '' !!}>{{ $name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Current Photo</label>
							<div class="col-md-6">
								<img src="/uploads/{{ $photo->image }}" width="100%">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">New Photo</label>
							<div class="col-md-6">
								<input type="file" class="form-control" name="photo" accept="image/*">
							</div>
						</div>
						
						{{-- Photo preview container, hidden as default with class hide from app.css --}}
						<div class="form-group hide" id="previewGrp">
							<label class="col-md-4 control-label">Preview</label>
							<div class="col-md-6">
								<img src="" width="100%" id="previewImg" />
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-4 control-label">Tags</label>
							<div class="col-md-6">
								{{-- bootstrap-tagsinput needs this to have 100% width to show properly --}}
								<input data-role="tagsinput" style="width:100%" type="text" class="form-control" name="tags" value="{{ old('tags', implode(', ', $photo->tags()->lists('title'))) }}">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="read_tags_from_file"> Try to read tags from file?
									</label>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Update Photo
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