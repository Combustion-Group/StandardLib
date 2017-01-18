<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 9:58 AM
 */

namespace Combustion\StandardLib\Assets\Models;


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

    public function validationRules():array
    {
        return [
            'mime'=>'required|string',
            'size'=>'required|numeric',
            'original_name'=>'required|string',
            'extension'=>'required|string',
            'url'=>'required|string',
        ];
    }
}
