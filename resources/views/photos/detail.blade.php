@extends('app')

@section('header_assets')

<meta name="keywords" content="{{ implode(', ', $photo->tags()->lists('title')) }}">
<meta name="description" content="{{ $photo->title }}">

{{-- Opengraph metas --}}
<meta property="og:title" content="{{ $photo->title }} - {{ config('whatthetag.site_name') }}" />
<meta property="og:description" content="{{ $photo->title }} on {{ url('/') }}" />
<meta property="og:url" content="{{ url('photo/detail/'. $photo->slug) }}" />
<meta property="og:image" content="{{ url('/').Croppa::url('uploads/'. $photo->image, 600, 315) }}" />
<meta property="og:type" content="website" />
{{-- / Opengraph metas --}}

{{-- Twitter Metas --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $photo->title }} - {{ config('whatthetag.site_name') }}" />
<meta name="twitter:description" content="{{ $photo->title }} on {{ url('/') }}" />
<meta name="twitter:url" content="{{ url('/') }}" />
<meta name="twitter:image:src" content="{{ url('/').Croppa::url('uploads/'. $photo->image, 600, 315) }}" />
{{-- /Twitter Metas --}}

<link rel="image_src" href="{{ url('/').Croppa::url('uploads/'. $photo->image, 600, 315) }}"/>
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
								<strong><span>Author:</span></strong>
								<span><a rel="tooltip" data-original-title="All photos of: {{ $photo->user->name }}" href="{{ url('photo/user/'.$photo->user->slug) }}">{{ $photo->user->name }}</a></span>
							</div>
							
							<div class="upper-margin">
								<h4>
									@forelse($photo->tags as $tag)
										<a class="label label-info" rel="tooltip" href="/photo/tagged/{{ $tag->slug }}" data-original-title="Photos tagged with: {{ $tag->title }}">{{ $tag->title }}</a>
									@empty
										<p>No tags found for this photo</p>
									@endforelse
								</h4>
							</div>
							
							@if(config('whatthetag.comments_enabled'))
								<hr>
								<div class="upper-margin">
									<div id="disqus_thread"></div>
									<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
								</div>
							@endif
							
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
									<input readonly value="[{{ config('whatthetag.site_name') }}]({{ url('/uploads/'.$photo->image) }})" type="text" class="form-control">
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
							
							
							<div class="upper-margin text-center">
								<a role="button" href="/uploads/{{ $photo->image }}" class="btn btn-lg btn-info" target="_blank" download>Download</a>
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
<script type="text/javascript">
$('ul[data-group="social-buttons"]').socialShare({
	image			: '{{ asset('uploads/'.$photo->image) }}',
	counts			: false,
	twitterVia		: '{{ config('whatthetag.twitter_name') }}',
	twitterHashTags	: '{{ implode(', ', $photo->tags()->lists('title')) }}',
});

var disqus_shortname = '{{ config('whatthetag.disqus_identifier') }}';
(function() {
	var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
	dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
	(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
})();

</script>
@stop