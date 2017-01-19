<?php
/**
 * Created by PhpStorm.
 * User: LaravelDude
 * Date: 1/18/17
 * Time: 10:17 AM
 */

namespace Combustion\StandardLib\Services\Assets\Traits;


use Combustion\StandardLib\Services\Assets\Models\Asset;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class IsDocument
 *
 * @package Combustion\StandardLib\Services\Assets\Traits
 * @author Luis A. Perez <lperez@combustiongroup.com>
 */
trait IsDocument
{
    /**
     * Documents can belong to many assets
     */
    public function asset():MorphMany
    {
        return $this->morphMany(Asset::class,'document','document_type');
    }

    /**
     * @param Asset $asset
     * @return Asset
     */
    public function attachToAsset(Asset $asset)
    {
        // pull resource
        $document = $this;
        // if resource already has asset
        if($document->hasAsset($asset))
        {
            // return resource
            return $asset;
        }
        // otherwise attach the asset to the resource
        $document->asset()->save($asset);
        return $asset;
    }

    /**
     * @param Asset $asset
     * @return bool
     */
    protected function hasAsset(Asset $asset)
    {
        $resource = $this;
        // for each asset
        foreach($resource->asset as $resource_asset)
        {
            // if it exists
            if($resource_asset->id == $asset->id)
            {
                return true;
            }
        }
        // other wise
        return false;
    }

}