<?php

namespace Combustion\StandardLib\Support\Testing;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Class TestCase
 *
 * @package Combustion\StandardLib\Support
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
abstract class TestCase extends BaseTestCase
{
    protected function output(string $message) : TestCase
    {
        fwrite(STDERR, $message . PHP_EOL);
        return $this;
    }
}
