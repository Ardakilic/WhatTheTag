@extends('app')

@section('header_assets')
{{-- View is totally based on this bootsnipp --}} 
{{-- http://bootsnipp.com/snippets/featured/thumbnail-caption-hover-effect --}}
<style type="text/css">
.thumbnail {
	position:relative;
	overflow:hidden;
}

.caption {
	position:absolute;
	top:0;
	right:0;
	background:rgba(66, 139, 202, 0.75);
	width:100%;
	height:100%;
	padding:2%;
	display: none;
	text-align:center;
	color:#fff !important;
	z-index:2;
}

#myModal {
	background: rgba(0, 0, 0, 0.75);
}

</style>
@endsection

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
								<div class="thumbnail">
									<div class="caption">
										<h4>{{ $photo->title }}</h4>
										<p><a href="#" data-toggle="modal" data-target="#myModal" data-img-url="/uploads/{{ $photo->image }}" data-img-title="{{ $photo->title }}" class="label label-danger" rel="tooltip" title="Zoom">Zoom</a></p>
										<p>
											<h5>Tags:</h5>
											<p class="tagsWrapper">
											@foreach($photo->tags as $tag)
												<a class="label label-info" rel="tooltip" href="#" title="Photos tagged with: {{ $tag->title }}">{{ $tag->title }}</a>
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
					
					{!! $photos->render() !!}
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

@section('footer_assets')
<script type="text/javascript">
$("[rel='tooltip']").tooltip();

$('.thumbnail').hover(
	function(){
		$(this).find('.caption').slideDown(250); //.fadeIn(250)
	},
	function(){
		$(this).find('.caption').slideUp(250); //.fadeOut(205)
	}
); 

{{-- Modal-related --}}
$('a[data-toggle="modal"]').click(function() {
	$('#myModal #myModalTitle').text($(this).attr('data-img-title'));
	$('#myModal img').attr('src', $(this).attr('data-img-url'));
	$('#myModal #modalDownloadBtn').attr('href', $(this).attr('data-img-url'));
	{{-- fetch the tags from container and add it to left footer of modal --}}
	$('#myModal #modalTagWrapper').html('<h4>' + $(this).closest('.caption').find('.tagsWrapper').html() + '<h4>');
});
{{-- /Modal-related --}}

</script>
@endsection