<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 10:17 AM
 */

namespace Combustion\StandardLib\Assets\Traits;


use Combustion\StandardLib\Assets\Models\Asset;

trait IsDocument
{
    /**
     * Get all of the comment's likes.
     */
    public function asset()
    {
        return $this->morphMany(Asset::class,'document','document_type');
    }
}