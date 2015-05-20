<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Tag extends Model implements SluggableInterface {
	
	use SluggableTrait;

	protected $sluggable = array(
		'build_from'	=> 'title',
		'save_to'		=> 'slug',
	);

	protected $fillable	= ['title'];
	
	public function photos() {
		return $this->belongsToMany('App\Photo', 'photo_tag')
			->withTimestamps();
	}
	
	public static function createAndReturnArrayOfTagIds($string, $delimiter = ',') {
		
		$tagsArray 		= explode($delimiter, $string);
		
		$ids = [];
		
		foreach($tagsArray as $tag) {
			
			$tag 		= trim($tag);
			$theTag 	= \App\Tag::firstOrCreate(['title' => $tag]);
			
			array_push($ids, $theTag->id);
		}
		
		return $ids;
	}

}
