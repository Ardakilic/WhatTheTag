<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\HasRandomStatementTrait;

use Storage;

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


        if ($uploadPath === null) {
            if (config('filesystems.cloud') === 'local') {
                $uploadPath = public_path() . '/' . config('whatthetag.uploads_folder') . '/';
            }
            $uploadPath = config('whatthetag.uploads_folder') . '/';
        }


        $fileName = time() . str_slug(getFileName($file->getClientOriginalName())) . '.' . $file->getClientOriginalExtension();

        // Now let's upload
        // With calling getDriver(), we can specift additional options upon uploading
        // This way, we can set the storage class on the fly, unlike the configuration
        Storage::disk(config('filesystems.cloud'))
            ->getDriver()
            ->put(
                $uploadPath . $fileName,
                file_get_contents($file),
                [
                    'StorageClass' => config('whatthetag.s3_storage_class', 'STANDARD'),
                ]
            );

        return ['filename' => $fileName];

    }

}