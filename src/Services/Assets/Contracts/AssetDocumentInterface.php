<?php

namespace Combustion\StandardLib\Services\Assets\Contracts;


use Combustion\StandardLib\Services\Assets\Models\Asset;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Interface AssetDocumentInterface
 *
 * @package Combustion\StandardLib\Services\Assets\Contracts
 * @author Luis A. Perez <lperez@combustiongroup.com>
 */
interface AssetDocumentInterface
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function asset() : MorphMany;

    /**
     * @return int
     */
    public function getId() : int;

    /**
     * @return \Combustion\StandardLib\Services\Assets\Models\Asset
     */
    public function attachToAsset() : Asset;
}