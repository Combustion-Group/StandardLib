<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/17/17
 * Time: 8:01 PM
 */

namespace Combustion\StandardLib\Uploader\src\Models;


use Combustion\StandardLib\Models\Model;

class File extends Model
{
    /**
     * @var string
     */
    protected $table = 'files';

    /**
     * @var array
     */
    protected $fillable = [
        'mime',
        'size',
        'original_name',
        'extension',
        'url'
    ];
}