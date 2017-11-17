<?php

namespace Combustion\StandardLib\Services\Assets\Contracts;

use Combustion\StandardLib\Services\Assets\Models\Asset;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;

/**
 * Interface Manipulator
 *
 * @package Combustion\StandardLib\Services\Assets\Contracts
 * @author Luis A. Perez <lperez@combustiongroup.com>
 */
interface Manipulator
{
    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return \Illuminate\Http\UploadedFile
     */
    public function manipulate(UploadedFile $file, array $options = []): array;

}