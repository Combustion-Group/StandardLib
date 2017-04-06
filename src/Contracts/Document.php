<?php

namespace Combustion\StandardLib\Contracts;

/**
 * Interface Document
 *
 * @package Combustion\StandardLib\Contracts
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
interface Document
{
    /**
     * @return array
     */
    public function toArrayDocument() : array;

    /**
     * @param array $document
     */
    public function fromArrayDocument(array $document);
}
