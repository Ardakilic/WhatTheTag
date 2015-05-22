@extends('app')

@section('header_assets')
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
							
						</div>
						
						<div class="col-md-4">
						
							<div style="padding-top: 0">
								<h3>
									<i class="fa fa-facebook"></i>
									<i class="fa fa-twitter"></i>
									<i class="fa fa-pinterest"></i>
									<i class="fa fa-google-plus"></i>
									<i class="fa fa-tumblr"></i>
									<i class="fa fa-reddit"></i>
								</h3>
							</div>
						
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
					
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer_assets')
<script type="text/javascript">
$('input[type="text"]').click(function(){
	$(this).focus().select();
});
</script>
@stop