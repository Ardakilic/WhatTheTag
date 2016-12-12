<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use DB;

use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\HasRandomStatementTrait;

class Photo extends Model
{

    use Sluggable;
    use HasRandomStatementTrait;

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

    protected $fillable = ['user_id', 'title', 'image'];

    public function tags()
    {
        return $this->belongsToMany('App\Tag', 'photo_tag')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function upload(UploadedFile $file, $uploadPath = null)
    {

        if (is_null($uploadPath)) {
            $uploadPath = public_path() . '/uploads/';
        }

        $fileName = str_slug(getFileName($file->getClientOriginalName())) . '.' . $file->getClientOriginalExtension();

        //Make file name unique so it doesn't overwrite any old photos
        while (file_exists($uploadPath . $fileName)) {
            $fileName = str_slug(getFileName($file->getClientOriginalName())) . '-' . str_random(5) . '.' . $file->getClientOriginalExtension();
        }
        $file->move($uploadPath, $fileName);

        return ['filename' => $fileName, 'fullpath' => $uploadPath . $fileName];

    }

}