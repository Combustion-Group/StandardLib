<?php
namespace Combustion\StandardLib\Services\Assets\Traits;


use Combustion\StandardLib\Services\Assets\Contracts\HasAssetsInterface;
use Combustion\StandardLib\Services\Assets\Models\Asset;

/**
 * Class HasAssets
 *
 * @package Combustion\StandardLib\Services\Assets\Traits
 * @author Luis A. Perez <lperez@combustiongroup.com>
 */
trait HasAssets
{
    /**
     * Get all of the assets for the post.
     */
    public function assets()
    {
        return $this->morphToMany(Asset::class,'resource','resource_asset','resource_id','asset_id')->withPivot('primary','resource_type')->withTimestamps();
    }

    /**
     * @return mixed
     */
    public function primaryAsset()
    {
        return $this->assets()->wherePivot('primary',1);
    }

    /**
     * Allows you to attach an asset to any model and if the asset
     * becomes primary it will change the flag for the previous
     * primary asset and set the new one as primary leaving
     * the previous one in the assets array. Also triggers
     * the event listener to bring asset url to the top
     * level of the model
     *
     * @param Asset $asset
     * @param bool $primary
     * @return HasAssetsInterface
     */
    public function attachAsset(Asset $asset,bool $primary = false) : HasAssetsInterface
    {
        // pull resource
        $resource = $this;
        // otherwise attach the asset to the resource
        if($primary)
        {
            // check if the current document can be adder as a primary asset
            $resource->takeOutExistingPrimaryAsset();
            // take out existing primary asset if any and save new asset at primary
            $resource->assets()->save($asset,['primary' => true]);
            // once save as primary we can trigger the listener
            $resource->bringPrimaryAssetUrlToTopLevelOfModel();
        }
        else
        {
            $resource->assets()->save($asset);
        }
        return $resource;
    }

    /**
     *
     */
    private function takeOutExistingPrimaryAsset() : bool
    {
        // get current model
        $resource = $this;
        // fetch the primary asset
        $primary_asset = $resource->primaryAsset()->first();
        // if its found
        if($primary_asset)
        {
            // take out primary
            $primary_asset->pivot->primary = false;
            // save
            $primary_asset->pivot->save();
        }
        return true;
    }

    /**
     * Grab the url of the primary asset that is
     * buried under four layers of data and
     * brings it to the to p level of the
     * model
     */
    public function bringPrimaryAssetUrlToTopLevelOfModel() : bool
    {
        // if the model implementing hasAssetsInterface has primaryAssetsField
        if(isset($this->primaryAssetsField))
        {
            // put all urls on the top level
            $this->$this->primaryAssetsField = [
                'original' => $this->primaryAsset()->get()->first()->document->image_file->url,
                'small' => $this->primaryAsset()->get()->first()->document->small_file->url,
//                'large' => $this->primaryAsset()->get()->first()->document->image_file->url,
                'medium' => $this->primaryAsset()->get()->first()->document->medium_file->url,
            ];
        }
        // if the object has primary assetField
        if(isset($this->primaryAssetField))
        {
            // just place the original image
            $this->$this->primaryAssetField = $this->primaryAsset()->get()->first()->document->image_file->url;
        }
        // save changes
        $this->save();
        return true;
        // this method can be overwritten inside of the model implementing hasAssetsInterface
    }
}