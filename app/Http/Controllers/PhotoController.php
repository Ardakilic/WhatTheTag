<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;
use App\Photo;
use App\Tag;
use DB;
use Str;
use Auth;

class PhotoController extends Controller {
	
	//Only users can access this route
	public function __construct()
	{
		$this->middleware('auth', ['only' => ['getNew', 'postNew']]);
		//I wish we could pass parameters to middlewares
		$this->middleware('validSlugFirstParameter', ['only' => ['getTagged', 'getDetail']]);
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$photos = Photo::with('tags')
			->random()
			->take(12)
			->get();
			
		return view('index')
			->withPhotos($photos);
	}
	
	public function getSearch(Request $request)
	{

		//Validation class maybe ? TODO
		if(!$request->has('q')) {
			return redirect('/')
				->withErrors('You must make a search from the bar first');
		}
		
		//Let's escape first
		$parameters = e($request->get('q'));
		
		$parameters	= explode(',', $parameters);
		//Values may be like "param1", " param2" after exploding, so we also need to trim them
		array_walk($parameters, 'trim');
		
		
		$photos = Photo::with('user', 'tags')
			
			//Let's search the title first
			->where(function($q) use ($parameters){
				
				$j = 0;
				foreach($parameters as $parameter) {
					
					if($j == 0) {
						$q->where('title', 'LIKE', '%'.$parameter.'%');
					} else {
						$q->orWhere('title', 'LIKE' ,'%'.$parameter.'%');
					}
				
					$j++;
				}
				
			})
		
			//Now, let's search the parameters in the tags
			->orWhere(function($q) use ($parameters) {
				
				$q->whereHas('tags', function($q2) use ($parameters) {
					
					$j = 0;
					foreach($parameters as $parameter) {
						
						if($j == 0) {
							$q2->where('title', 'LIKE', '%'.$parameter.'%');
						} else {
							$q2->orWhere('title', 'LIKE','%'.$parameter.'%');
						}
						
						$j++;
					}
				});
				
			})
			
			//Lastly, let's search in the user name
			->orWhere(function($q) use ($parameters){
				
				$q->whereHas('user', function($q2) use ($parameters){
					
					$j = 0;
					foreach($parameters as $parameter) {
						
						if($j == 0) {
							$q2->where('name', 'LIKE', '%'.$parameter.'%');
						} else {
							$q2->orWhere('name', 'LIKE', '%'.$parameter.'%');
						}
						
						$j++;
					}
					
				});
			})
			->orderBy('id', 'desc')
			->paginate(2);
			
			return view('photos.list')
				->withHeading('Search results for: '.implode(', ', $parameters))
				->withPhotos($photos);
			
	}
	
	public function getRecents()
	{
		$photos = Photo::with('tags')
			->take(12)
			->orderBy('id', 'desc')
			->paginate(2);
			
		return view('photos.list')
			->withHeading('Recent Photos')
			->withPhotos($photos);
	}
	
	public function getTagged($tagSlug)
	{
		$tag = Tag::with([
				'photos' => function($q){
					$q->orderBy('id', 'desc');
				}, 
				'photos.tags',
			])
			->whereSlug($tagSlug) //I didn't like findBySlug() provided with Sluggable package
			->first();

		if(!$tag) {
			return redirect('/')
				->withError('Tag '.$tagSlug.' not found');
		}

		return view('photos.list')
			->withHeading('Photos Tagged With: '.$tagSlug)
			->withPhotos($tag->photos()->paginate(2));
	}
	
	public function getDetail($photoSlug) {
		
		$photo = Photo::with('tags', 'user')
			->whereSlug($photoSlug)
			->first();
			
		if(!$photo) {
			return redirect('/')
				->withError('Photo not found');
		}
		
		return view('photos.detail')
			->withPhoto($photo);
	}
	

	public function getNew()
	{
		return view('photos.new');
	}
	
	public function postNew(Request $request)
	{
		
		$validation = Validator::make($request->all(), [
			'title'		=> 'required|min:3',
			'photo'		=> 'required|image',
			'tags'		=> 'required'
		]);
		
		if($validation->fails()) {
			return redirect()
				->back()
				->withInput()
				->withErrors($validation);
		}
		
		//Upload the image and return the filename and full path
		$upload			= Photo::upload($request->file('photo'));
		
		//Tag Stuff
		//First, create(if needed) and return IDs of tags
		$tagIds			= Tag::createAndReturnArrayOfTagIds($request->get('tags'));
		
		//If user wants to read the tags (keywords) from the file, then we need to fetch them from uploaded file.
		if($request->has('read_tags_from_file')) {
			$exif = exif_read_data($upload['fullpath'], 'ANY_TAG', true);
			if($exif) {
				if(array_key_exists('IFD0', $exif)) {
					if(array_key_exists('Keywords', $exif['IFD0'])) {
						//array_unique, because same tags may be on both the form and the file, but only one is added to the database
						//Keywords are delimited by semicolon ( ; ) in the EXIF tag data.
						$tagIds = array_unique(array_merge(
								$tagIds,
								Tag::createAndReturnArrayOfTagIds($exif['IFD0']['Keywords'], ';')
						));
					}
				}
			}
		}
		//Tag Stuff end
		
		$photo			= new Photo;
		$photo->user_id	= Auth::user()->id;
		$photo->title	= $request->get('title');
		$photo->image	= $upload['filename'];
		$photo->save();
		
		//Now attach the tags, since this is creating method, attach() is okay
		$photo->tags()->attach($tagIds);
		
		return redirect()
			->back()
			->withSuccess('Photo Created Successfully!');
		
	}

}