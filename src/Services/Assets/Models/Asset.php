<?php
namespace Combustion\StandardLib\Services\Assets\Models;



use Combustion\StandardLib\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'order',
        'document_id',
        'document_type',
        'active',
        'user_id'
    ];

    /**
     * Allows base controller to carry the global
     * Active scope for all models.
     * @var string
     */
    public $activeField = 'active';

    /**
     * @var array
     */
    protected $with = ['document'];

    /*
     * RELATIONSHIPS
     */


    /**
     * Get all of the owning document models.
     */
    public function document()
    {
        return $this->morphTo();
    }

    /*
     * RELATIONSHIPS
     */

    /*
     * SCOPES
     */
    public function scopeOfType($query,$string)
    {
        $query->where('document_type',$string);
    }
    /*
     * SCOPES
     */
}