<?php
namespace Combustion\StandardLib\Services\Assets\Models;


use Combustion\StandardLib\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;
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
