<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class Tag extends Model
{

    use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $fillable = ['title'];

    public function photos()
    {
        return $this->belongsToMany('App\Photo', 'photo_tag')
            ->withTimestamps();
    }

    public static function createAndReturnArrayOfTagIds($string, $delimiter = ',')
    {
        $tagsArray = explode($delimiter, $string);

        $ids = [];

        foreach ($tagsArray as $tag) {

            $tag = trim($tag);
            $theTag = \App\Tag::firstOrCreate(['title' => $tag]);

            array_push($ids, $theTag->id);
        }

        return $ids;
    }

}