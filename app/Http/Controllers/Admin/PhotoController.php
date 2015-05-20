<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Photo;
use Datatables;
use Validator;
use Croppa;

class PhotoController extends Controller {
	
	//Only admins can access this controller
	public function __construct() {
		$this->middleware('auth.admin');
	}
	
	public function getIndex() {
		return view('admin.photos.list');
	}
	
	
	public function getGrid() {
		
		$photos = Photo::leftJoin('users', 'users.id', '=', 'photos.user_id')
			->select(['photos.id', 'photos.image as image', 'photos.title', 'users.name as user_name', 'users.id as user_id', 'photos.created_at', 'photos.updated_at']);
		
		return Datatables::of($photos)
			->addColumn('action', function ($photo) {
				return '<a href="/admin/photos/edit/'.$photo->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a> <a href="/admin/photos/delete/'.$photo->id.'" class="btn btn-xs btn-primary delete-button"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
			})
			->editColumn('image', '<a href="#" data-toggle="modal" data-target="#myModal" data-img-url="/uploads/{{ $image }}" data-img-title="{{ $title }}"><img data-toggle="tooltip" title="Click for bigger version" src="{{ Croppa::url("/uploads/$image", 150, 120) }}" /></a>')
			->editColumn('user_name', '<a href="/admin/users/edit/{{ $user_id }}">{{ $user_name }}</a>')
			->removeColumn('user_id')
			->make(true);
		
	}

}
