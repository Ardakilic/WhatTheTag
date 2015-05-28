@extends('app')

@section('header_assets')

<meta name="keywords" content="{{ implode(', ', $photo->tags()->lists('title')) }}">
<meta name="description" content="{{ $photo->title }}">

{{-- Opengraph metas --}}
<meta property="og:title" content="{{ $photo->title }}" />
<meta property="og:description" content="{{ $photo->title }} on {{ url('/') }}" />
<meta property="og:url" content="{{ url('photo/detail/'. $photo->slug) }}" />
<meta property="og:image" content="{{ url('/').Croppa::url('uploads/'. $photo->image, 600, 315) }}" />
<meta property="og:type" content="website" />
{{-- / Opengraph metas --}}
@endsection

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Photo: {{ $photo->title }}</div>
				<div class="panel-body">
					
					<div class="row">
						
						<div class="col-md-8">
							
							<img class="img-responsive" src="/uploads/{{ $photo->image }}">
							
							<div class="upper-margin">
								<a role="button" href="/uploads/{{ $photo->image }}" class="btn btn-default" target="_blank" download>Download</a>
							</div>
						</div>
						
						<div class="col-md-4">
						
	
							<ul class="list-inline" data-group="social-buttons">
								<li>
									<a class="btn btn-social-icon btn-twitter twitter-share">
										<i class="fa fa-twitter"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-facebook facebook-share">
										<i class="fa fa-facebook"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-google-plus gplus-share">
										<i class="fa fa-google"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-linkedin linkedin-share">
										<i class="fa fa-linkedin"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-pinterest pinterest-share">
										<i class="fa fa-pinterest"></i>
									</a>
								</li>
								
							</ul>
							
							<div data-group="share-buttons">
							
								<div class="form-group">
									<label for="exampleInputEmail1">Image Link (email & IM)</label>
									<input readonly value="{{ url('photo/detail/'.$photo->slug) }}" type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="exampleInputEmail1">Direct Link (email & IM)</label>
									<input readonly value="{{ url('/uploads/'.$photo->image) }}" type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="exampleInputEmail1">Markdown Link (Reddit Comments)</label>
									<input readonly value="[whatthetag]({{ url('/uploads/'.$photo->image) }})" type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="exampleInputEmail1">HTML (website / blogs)</label>
									<input readonly value="{{ '<a href="'. url('photo/detail/'.$photo->slug) .'"><img src="'. url('/uploads/'.$photo->image) .'" title="source: '. url('/') .'"></a>' }}" type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="exampleInputEmail1">BBCode (message boards & forums)</label>
									<input readonly value="[img]{{ url('/uploads/'.$photo->image) }}[/img]" type="text" class="form-control">
								</div>
								
								<div class="form-group">
									<label for="exampleInputEmail1">Linked BBCode (message boards)</label>
									<input readonly value="[url={{ url('/photo/detail/'.$photo->slug) }}[img]{{ url('/uploads/'.$photo->image) }}[/img][/url]" type="text" class="form-control">
								</div>
							</div>
						
						</div>
						
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer_assets')
<script>
$('ul[data-group="social-buttons"]').socialShare({
	image			: '{{ asset('uploads/'.$photo->image) }}',
	twitterVia		: 'ardadev', //TODO
	twitterHashTags	: '{{ implode(', ', $photo->tags()->lists('title')) }}',
});
</script>
@stop