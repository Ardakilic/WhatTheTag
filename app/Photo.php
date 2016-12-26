<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\HasRandomStatementTrait;
use AlgoliaSearch\Laravel\AlgoliaEloquentTrait;

use Storage;
use Croppa;

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


    public $indices;

    // $autoIndex is set to false, because there is a pivot relationship and this doesn't fire the event
    // Delete can still be done automatically
    public static $autoIndex = false;
    public static $autoDelete = true;

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);
        $this->indices = [config('algolia.connections.' . config('algolia.default') . '.indice_name')];
    }

    public function getAlgoliaRecord()
    {
        /**
         * Load the user relation so that it's available
         *  in the laravel toArray method
         */
        $this->user;
        $this->tags;

        // When we set this method, $algoliaSettings is ignored, so we've to strip the function this:
        $allowed = $this->algoliaSettings['searchableAttributes'];
        $elements = array_intersect_key($this->toArray(), array_flip($allowed));

        return array_merge($elements, [
            'img_src' => config('whatthetag.s3_storage_cdn_domain') . config('whatthetag.uploads_folder') . '/' . $this->image,
            'thumb_src' => Croppa::url('/' . config('whatthetag.uploads_folder') . '/' . $this->image, 400, 300),
            //'url' => config('app.url') . '/photo/detail/' . $this->slug,
            'user_name' => $this->user->name,
            'tags' => array_map(function ($data) {
                return [
                    'title' => $data['title'],
                    'slug' => $data['slug'],
                ];
            }, $this->tags->toArray())
        ]);
    }


    public $algoliaSettings = [

        'attributesToHighlight' => [
            'title',
            'user_name',
            'tags',
        ],
        'searchableAttributes' => [
            'id',
            'title',
            'slug',
            'img_src',
            'thumb_src',
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

                    // Set visibility to public
                    'ACL' => 'public-read',

                    //Let's add a decade for cache headers:
                    'CacheControl' => 'max-age=315360000, public',
                    'Expires' => gmdate("D, d M Y H:i:s", time() + 315360000) . " GMT",
                ]
            );

        return ['filename' => $fileName];

    }

}