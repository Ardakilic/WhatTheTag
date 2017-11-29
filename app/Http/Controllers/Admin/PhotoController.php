<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Photo;
use App\User;
use App\Tag;
//use Datatables;
use Validator;
use Croppa;

class PhotoController extends Controller
{

    //Only admins can access this controller
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    public function getIndex()
    {
        return view('admin.photos.list')
            ->withTitle('List Photos');
    }


    public function getGrid()
    {
        $photos = Photo::leftJoin('users', 'users.id', '=', 'photos.user_id')
            ->select([
                'photos.id as id', 'photos.image as image', 'photos.title as title',
                'users.name', 'users.id as user_id', 'photos.created_at as created_at',
                'photos.updated_at as updated_at'
            ]);

        return datatables()->of($photos)
            ->addColumn('action', function ($photo) {
                return '<a href="/admin/photos/edit/' . $photo->id . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a> <a href="/admin/photos/delete/' . $photo->id . '" class="btn btn-xs btn-primary delete-button"><i class="glyphicon glyphicon-remove"></i> Delete</a>';
            })
            ->editColumn('image', '<a href="#" data-modal-type="admin-modal" data-toggle="modal" data-target="#myModal" data-img-url="{{ (config(\'filesystems.cloud\') == \'s3\' ? config(\'whatthetag.s3_storage_cdn_domain\') : \'\') . config(\'whatthetag.uploads_folder\') }}/{{ $image }}" data-img-title="{{ $title }}"><img class="thumbnail" data-toggle="tooltip" rel="thumbnail" title="Click for bigger version" src="{{ Croppa::url(\'/\'. config(\'whatthetag.uploads_folder\') .\'/\'.$image, 150, 120) }}" /></a>')
            ->editColumn('name', '<a href="/admin/users/edit/{{ $user_id }}">{{ $name }}</a>')
            ->removeColumn('user_id')
            //https://github.com/yajra/laravel-datatables/issues/949#issuecomment-275834424
            ->rawColumns(['action', 'image', 'name'])
            ->make(true);
    }


    // Creation methods are exactly the same as the PhotoController@getNew and postNew, so no need to copy-paste here.

    public function getEdit($id)
    {
        $photo = Photo::with(['user', 'tags'])->find($id);
        if (!$photo) {
            return back()
                ->withError('Photo not found.');
        }

        //We also need a dropdown for users
        $users = User::pluck('name', 'id');

        return view('admin.photos.edit')
            ->withTitle('Editing Photo: ' . $photo->title)
            ->withPhoto($photo)
            ->withUsers($users);

    }

    public function postEdit($id, Request $request)
    {
        $photo = Photo::with(['user', 'tags'])->find($id);

        if (!$photo) {
            return back()
                ->withError('Photo not found.');
        }

        $validation = Validator::make($request->all(), [
            'title' => 'required|min:2',
            'photo' => 'image',
            'user_id' => 'required|integer|exists:users,id',
            'tags' => 'required'
        ]);

        if ($validation->fails()) {
            return back()
                ->withInput()
                ->withErrors($validation);
        }

        $isFileUploaded = $request->hasFile('photo');

        if ($isFileUploaded) {

            //First, delete the old photos and all sizes using Croppa
            try {
                Croppa::delete(config('whatthetag.uploads_folder') . '/' . $photo->image);
            } catch(\Exception $e) {
                // Remote source may not be there for some reasons, we don't want exception to be thrown here
                // You can log $e->getMessage() here.
            }

            //Then Upload the image and return the filename
            $upload = Photo::upload($request->file('photo'));

        }

        //Tag Stuff
        //First, create(if needed) and return IDs of tags
        $tagIds = Tag::createAndReturnArrayOfTagIds($request->get('tags'));

        /*//If user wants to read the tags (keywords) from the file, then we need to fetch them from uploaded file.
        if ($isFileUploaded && $request->has('read_tags_from_file')) {
            $exif = exif_read_data($upload['fullpath'], 'ANY_TAG', true);
            if ($exif) {
                if (array_key_exists('IFD0', $exif)) {
                    if (array_key_exists('Keywords', $exif['IFD0'])) {
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
        //Tag Stuff end*/

        $photo->user_id = $request->get('user_id');
        $photo->title = $request->get('title');
        if ($isFileUploaded) {
            $photo->image = $upload['filename'];
        }
        $photo->save();

        //Now, sync all the associated tags
        $photo->tags()->sync($tagIds);

        // Push to Algolia, auto-index disabled because event is fired before pivot table sync.
        $photo->searchable();

        return back()
            ->withSuccess('Photo updated successfully!');
    }

    public function getDelete($id)
    {
        $photo = Photo::find($id);

        if (!$photo) {
            return back()
                ->withError('Photo not found');
        }

        // First delete the photo
        Croppa::delete(config('whatthetag.uploads_folder') . '/' . $photo->image);

        //If foreign keys were not added to pivot table as on delete cascade, we also needed to delete the tags beforehand
        //I'm leaving this here just for reference
        //$photo->tags()->detach();

        $photo->delete();

        // Remove From Algolia
        // Commented-out, because event can handle this well enough,
        // unlike upon creation and update which also has to mess with pivot table stuff
        // $photo->removeFromIndex();

        return back()
            ->withSuccess('Photo deleted successfully!');
    }

}