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
		
		//First, create(if needed) and return IDs of tags
		$tagIds			= Tag::createAndReturnArrayOfTagIds($request->get('tags'));
		
		$photo 			= new Photo;
		$photo->user_id	= Auth::user()->id;
		$photo->title 	= $request->get('title');
		$photo->image	= $upload;
		$photo->save();
		
		//Now attach the tags, since this is creating method, attach() is okay
		$photo->tags()->attach($tagIds);
		
		return redirect()
			->back()
			->withSuccess('Photo Created Successfully!');
		
	}

}