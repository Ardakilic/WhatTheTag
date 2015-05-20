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
	public function __construct() {
		$this->middleware('auth', ['except' => 'getIndex']);
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

	public function getNew() {
		return view('photos.new');
	}
	
	public function postNew(Request $request) {
		
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
		
		//Upload the image and return the filename
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
						//Keywords are delimited by semicolon ( ; ) in the tag data.
						$tagIds = array_unique(array_merge(
								$tagIds,
								Tag::createAndReturnArrayOfTagIds($exif['IFD0']['Keywords'], ';')
						));
					}
				}
			}
		}
		//Tag Stuff dnd
		
		$photo 			= new Photo;
		$photo->user_id	= Auth::user()->id;
		$photo->title 	= $request->get('title');
		$photo->image	= $upload['filename'];
		$photo->save();
		
		//Now attach the tags, since this is creating method, attach() is okay
		$photo->tags()->attach($tagIds);
		
		return redirect()
			->back()
			->withSuccess('Photo Created Successfully!');
		
	}

}