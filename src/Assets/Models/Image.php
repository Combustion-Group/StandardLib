<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 9:59 AM
 */

namespace Combustion\StandardLib\Assets\Models;


use Combustion\StandardLib\Assets\Contracts\AssetDocumentInterface;
use Combustion\StandardLib\Assets\Traits\IsDocument;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 *
 * @package Combustion\StandardLib\Assets\Models
 */
class Image extends Model implements AssetDocumentInterface
{
    use IsDocument;
    /**
     * @var array
     */
    protected $fillable = [
        'image_id',
        'small_id',
        'medium_id',
        'title',
        'slug',
        'description'
    ];

    /**
     * @var array
     */
    protected $with = ['image_file'];
    /*
     * RELATIONSHIPS
     */

    public function getId(): int
    {
        return (int)$this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function image_file()
    {
        return $this->hasOne('App\File','id','image_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function small_file()
    {
        return $this->hasOne('App\File','id','small_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function medium_file()
    {
        return $this->hasOne('App\File','id','medium_id');
    }

}
