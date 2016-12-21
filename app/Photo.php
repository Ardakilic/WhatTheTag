<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\HasRandomStatementTrait;
use AlgoliaSearch\Laravel\AlgoliaEloquentTrait;

use Storage;

class Photo extends Model
{

    use Sluggable;
    use HasRandomStatementTrait;
    use AlgoliaEloquentTrait;

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


    public $indices = ['whatthetag'];
    public static $autoIndex = true;
    public static $autoDelete = true;

    public function getAlgoliaRecord()
    {
        /**
         * Load the user relation so that it's available
         *  in the laravel toArray method
         */
        $this->user;
        $this->tags;

        // When we set this method, $algoliaSettings is ignored, so we've to strip the function this:
        $allowed = $this->algoliaSettings['attributesToIndex'];
        $elements = array_intersect_key($this->toArray(), array_flip($allowed));

        return array_merge($elements, [
            'img_src' => config('whatthetag.s3_storage_cdn_domain') . config('whatthetag.uploads_folder') . '/' . $this->image,
            'url' => config('app.url') . '/photo/detail/' . $this->slug,
            'user_name' => $this->user->name,
            'tags' => array_map(function ($data) {
                return $data['title'];
            }, $this->tags->toArray())
        ]);
    }


    public $algoliaSettings = [
        'attributesToIndex' => [
            'id',
            'user_id',
            'title',
            'url',
            'image', //we may use this on croppa?
            'img_src',
            'user_name',
            'tags',
        ],
        'customRanking' => [
            'desc(popularity)',
            //'desc(id)',
        ],
    ];


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

    public static function upload(UploadedFile $file)
    {

        $fileName = time() . str_slug(getFileName($file->getClientOriginalName())) . '.' . $file->getClientOriginalExtension();

        // Now let's upload
        // With calling getDriver() method, we can specify additional options upon uploading
        // This way, we can set the storage class on the fly, unlike the Laravel's default configuration
        Storage::disk(config('filesystems.cloud'))
            ->getDriver()
            ->put(
                $fileName,
                file_get_contents($file),
                [
                    'StorageClass' => config('whatthetag.s3_storage_class', 'STANDARD'),
                    // https://github.com/thephpleague/flysystem-aws-s3-v3/blob/dc56a8faf3aff0841f9eae04b6af94a50657896c/src/AwsS3Adapter.php#L387
                    'ACL' => 'public-read',
                ]
            );

        return ['filename' => $fileName];

    }

}