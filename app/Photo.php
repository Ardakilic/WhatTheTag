<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Photo extends Model implements SluggableInterface {

	use SluggableTrait;
	
	protected $sluggable = array(
		'build_from'	=> 'title',
		'save_to'		=> 'slug',
	);

	protected $fillable = ['user_id', 'title', 'image'];
	
	public function tags() {
		return $this->belongsToMany('App\Tag', 'photo_tag')->withTimestamps();
	}
	
	public static function upload(UploadedFile $file, $uploadPath = null) {
		
		if(is_null($uploadPath)) {
			$uploadPath = public_path().'/uploads/';
		}
		
		$fileName 	= str_slug(getFileName($file->getClientOriginalName())).'.'.$file->getClientOriginalExtension();
		
		//Make file name unique so it doesn't overwrite any old photos
		while(file_exists($uploadPath.$fileName)) {
			$fileName = str_slug(getFileName($file->getClientOriginalName())).'-'.str_random(5).'.'.$file->getClientOriginalExtension();
		}
		$file->move($uploadPath, $fileName);
		
		return $fileName;
		
	}

}