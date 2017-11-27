<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;
use App\Photo;
use App\Tag;
use App\User;
use DB;
use Str;
use Auth;

class PhotoController extends Controller
{

    //Only users can access this route
    public function __construct()
    {
        $this->middleware('auth', ['only' => ['getNew', 'postNew']]);
        //I wish we could pass parameters to middlewares
        $this->middleware('validSlugFirstParameter', ['only' => ['getTagged', 'getDetail', 'getUser']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        $photos = Photo::with('tags')
            ->orderByRandom()
            ->take(12)
            ->get();

        return view('photos.list')
            ->withPhotos($photos);
    }

    public function getSearch()
    {
        return view('photos.search')
            ->withTitle('Search for a Photo!');

    }

    public function getRecents()
    {
        $photos = Photo::with('tags')
            ->take(12)
            ->orderBy('id', 'desc')
            ->paginate(config('whatthetag.pagination_count'));

        return view('photos.list')
            ->withTitle('Recent Photos')
            ->withPhotos($photos);
    }

    public function getTagged($tagSlug)
    {
        $tag = Tag::with([
            'photos' => function ($q) {
                $q->orderBy('id', 'desc');
            },
            'photos.tags',
        ])
            ->whereSlug($tagSlug)//I didn't like findBySlug() provided with Sluggable package
            ->first();

        if (!$tag) {
            return redirect('/')
                ->withError('Tag ' . $tagSlug . ' not found');
        }

        return view('photos.list')
            ->withTitle('Photos Tagged With: ' . $tagSlug)
            ->withPhotos($tag->photos()->paginate(config('whatthetag.pagination_count')));
    }

    public function getUser($userSlug)
    {
        //Let's find the user first
        //I don't like findBySlug() method 
        $user = User::with('photos', 'photos.tags', 'photos.user')
            ->whereSlug($userSlug)->first();

        if (!$user) {
            return redirect('/')
                ->withError('User not found');
        }

        return view('photos.list')
            ->withTitle('All Photos of: ' . $user->name)
            ->withPhotos($user->photos()->paginate(config('whatthetag.pagination_count')));

    }

    public function getDetail($photoSlug)
    {

        $photo = Photo::with('tags', 'user')
            ->whereSlug($photoSlug)
            ->first();

        if (!$photo) {
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
            'title' => 'required|min:2',
            'photo' => 'required|image',
            'tags' => 'required'
        ]);

        if ($validation->fails()) {
            return back()
                ->withInput()
                ->withErrors($validation);
        }

        //Upload the image and return the filename and full path
        $upload = Photo::upload($request->file('photo'));

        //Tag Stuff
        //First, create(if needed) and return IDs of tags
        $tagIds = Tag::createAndReturnArrayOfTagIds($request->get('tags'));

        /*//If user wants to read the tags (keywords) from the file, then we need to fetch them from uploaded file.
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
        }*/
        //Tag Stuff end

        $photo = new Photo;
        $photo->user_id = Auth::id();
        $photo->title = $request->get('title');
        $photo->image = $upload['filename'];
        $photo->save();

        //Now attach the tags, since this is creating method, attach() is okay
        $photo->tags()->attach($tagIds);

        // Re-Push to Algolia, auto-index disabled because event is fired before pivot table sync.
        $photo->searchable();

        return back()
            ->withSuccess('Photo Created Successfully!');

    }

}