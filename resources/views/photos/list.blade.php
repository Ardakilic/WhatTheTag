@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">{{ $heading or 'Photos' }}</div>

				<div class="panel-body">
					
					@include('partials.messages')
					
					<div class="row">
						
						@forelse($photos as $photo)
						
							<div class="col-md-3">
								<div class="thumbnail list-thumbnail">
									<div class="caption list-caption">
										<h4><a rel="tooltip" data-placement="bottom" title="go to photo" href="/photo/detail/{{ $photo->slug }}">{{ $photo->title }}</a></h4>
										<p><a href="#" data-link-type="list-modal" data-toggle="modal" data-target="#myModal" data-img-url="/uploads/{{ $photo->image }}" data-img-title="{{ $photo->title }}" class="label label-danger" rel="tooltip index-modal" title="Zoom">Zoom</a></p>
										<p>
											<h5>Tags:</h5>
											<p class="tagsWrapper">
											@foreach($photo->tags as $tag)
												<a class="label label-info" rel="tooltip" href="/photo/tagged/{{ $tag->slug }}" title="Photos tagged with: {{ $tag->title }}">{{ $tag->title }}</a>
											@endforeach
											</p>
										</p>
									</div>
									<img src="{{ Croppa::url('/uploads/'.$photo->image, 400, 300) }}" alt="{{ $photo->title }}">
								</div>
							</div>
						
						@empty
							<div class="col-md-12 text-center">No photos found :(</div>
						@endforelse
					
					
					</div>
					
					<div class="col-md-12 text-center">{!! $photos->render() !!}</div>
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
						<a id="modalDownloadBtn" role="button" href="" class="btn btn-default" target="_blank" download>Download</a>
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{-- /Modal --}}

@endsection