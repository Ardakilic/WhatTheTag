<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User;
use Hash;
use Datatables;
use Validator;

class UserController extends Controller {
	
	//Only admins can access this controller
	public function __construct() {
		$this->middleware('auth.admin');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('admin.users.list');
	}
	
	public function getGrid() {
		
		$users = User::select(['id', 'name', 'email', 'role', 'created_at', 'updated_at']);
		
		return Datatables::of($users)
		->addColumn('action', function ($user) {
			return '<a href="/admin/users/edit/'.$user->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a> <a href="/admin/users/delete/'.$user->id.'" class="btn btn-xs btn-primary delete-button"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
		})
		->make(true);
		
		
	}
	
	public function getNew() {
		return view('admin.users.new');
	}
	
	public function postNew(Request $request) {
		
		$validation = Validator::make($request->all(), [
			'name'					=> 'required|min:3|unique:users,name',
			'email'					=> 'required|email|unique:users,email',
			'role'					=> 'required|in:user,admin',
			'password'				=> 'required|min:8',
			'password_confirmation'	=> 'required|same:password',
		]);
		
		if($validation->fails()) {
			return redirect()
				->back()
				->withInput()
				->withErrors($validation);
		}
		
		$user 			= New User;
		$user->name		= $request->get('name');
		$user->email	= $request->get('email');
		$user->role		= $request->get('role');
		$user->password	= Hash::make($request->get('password'));
		$user->save();
		
		return redirect()
			->back()
			->withSuccess('User created successfully!');
		
	}
	

	public function getEdit($id) {
		
		$user = User::find($id);
		
		if(!$user) {
			return redirect('/admin/users/')
				->withError('User not found!');
		}
		
		return view('admin.users.edit')
			->withUser($user);
		
	}
	
	public function postEdit($id, Request $request) {
		
		$user = User::find($id);
		
		if(!$user) {
			return redirect('/admin/users/')
				->withError('User not found!');
		}
	
		$validation = Validator::make($request->all(), [
			'name'					=> 'required|min:3',
			'role'					=> 'required|in:user,admin',
			'password'				=> 'min:8',
			'password_confirmation'	=> 'required_with:password|same:password',
		]);
		
		if($validation->fails()) {
			return redirect()
				->back()
				->withInput()
				->withErrors($validation);
		}
		
		$user->name	= $request->get('name');
		$user->role	= $request->get('role');
		if(strlen($request->get('password'))) {
			$user->password = Hash::make($request->get('password'));
		}
		$user->save();
		
		return redirect()
			->back()
			->withSuccess('User updated successfully!');
		
	}
	
	public function getDelete($id) {
		
		$user = User::find($id);
		
		if(!$user) {
			return redirect('/admin/users/')
				->withError('User not found!');
		}
		
		if($user->role == 'admin') {
			return redirect()
				->back()
				->withError('You cannot delete an administrator, first you must change the account level as user');
		}
		
		$user->delete();
		
		return redirect()
			->back()
			->withSuccess('User deleted successfully!');
	}
	

}
