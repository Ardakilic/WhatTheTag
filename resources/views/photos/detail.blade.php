@extends('app')

@section('header_assets')
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

{{-- Social buttons CSS --}}
{{-- http://lipis.github.io/bootstrap-social/ --}}
<link rel="stylesheet" href="//cdn.rawgit.com/lipis/bootstrap-social/a14ffc82e1599b5020ab149b916b008ddc75fb4a/bootstrap-social.css">

{{-- Opengraph metas --}}
<meta property="og:title" content="{{ $photo->title }}" />
<meta property="og:description" content="{{ $photo->title }} on {{ url('/') }}" />
<meta property="og:url" content="{{ url('photo/detail/'. $photo->slug) }}" />
<meta property="og:image" content="{{ url('/').Croppa::url('uploads/'. $photo->image, 600, 315) }}" />
<meta property="og:type" content="website" />
{{-- / Opengraph metas --}}

<style type="text/css">
.upper-margin {
	margin-top: 20px;
}
</style>
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
						
	
							<ul class="list-inline">
								<li>
									<a href="" class="btn btn-social-icon btn-twitter ssk-twitter">
										<i class="fa fa-twitter"></i>
									</a>
								</li>
								
								<li>
									<a href="" class="btn btn-social-icon btn-facebook ssk-facebook">
										<i class="fa fa-facebook"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-google ssk-google-plus">
										<i class="fa fa-google-plus"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-linkedin ssk-linkedin">
										<i class="fa fa-linkedin"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-pinterest ssk-pinterest">
										<i class="fa fa-pinterest"></i>
									</a>
								</li>
								
								<li>
									<a class="btn btn-social-icon btn-tumblr ssk-tumblr">
										<i class="fa fa-tumblr"></i>
									</a>
								</li>
							</ul>
						
							<div class="form-group">
								<label for="exampleInputEmail1">Image Link (email & IM)</label>
								<input readonly value="{{ url('photo/detail/'.$photo->slug) }}" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
							</div>
							
							<div class="form-group">
								<label for="exampleInputEmail1">Direct Link (email & IM)</label>
								<input readonly value="{{ url('/uploads/'.$photo->image) }}" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
							</div>
							
							<div class="form-group">
								<label for="exampleInputEmail1">Markdown Link (Reddit Comments)</label>
								<input readonly value="[whatthetag]({{ url('/uploads/'.$photo->image) }})" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
							</div>
							
							<div class="form-group">
								<label for="exampleInputEmail1">HTML (website / blogs)</label>
								<input readonly value="{{ '<a href="'. url('photo/detail/'.$photo->slug) .'"><img src="'. url('/uploads/'.$photo->image) .'" title="source: '. url('/') .'"></a>' }}" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
							</div>
							
							<div class="form-group">
								<label for="exampleInputEmail1">BBCode (message boards & forums)</label>
								<input readonly value="[img]{{ url('/uploads/'.$photo->image) }}[/img]" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
							</div>
							
							<div class="form-group">
								<label for="exampleInputEmail1">Linked BBCode (message boards)</label>
								<input readonly value="[url={{ url('/photo/detail/'.$photo->slug) }}[img]{{ url('/uploads/'.$photo->image) }}[/img][/url]" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
							</div>
						
						</div>
						
					</div>
					
					
					<div class="row">
					
						
						<ul class="element">
<!--===== this block will become clickable (shows the twitter share dialogue on click) 
because of the class 'twitter-share'====-->
    <li>
    
    <div class="twitter-share">
        <span>Share on twitter</span>
        <span class="twitter-count"></span> <!--====shows twitter share count==-->
    </div>
    
    </li>
    
    
    <li>
    
    <div class="facebook-share">
        <span>Share on fb</span>
        <span class="facebook-count"></span> <!--====shows twitter share count==-->
    </div>
    
    </li>
    

</ul>


					
					
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer_assets')
{{-- Social Sharing Kit, we are only using the JS --}}
<script type="text/javascript" src="//cdn.rawgit.com/darklow/social-share-kit/v1.0.4/dist/js/social-share-kit.min.js"></script>
<script type="text/javascript">
SocialShareKit.init({
	selector: '.btn-social-icon',
	url: '{{ url('photo/detail/'.$photo->slug) }}',
	text: '{{ $photo->title }} on {{ url('/') }}'
});
{{-- /Social Sharing Kit --}}


$('input[type="text"]').click(function(){
	$(this).focus().select();
});
</script>
@endsection