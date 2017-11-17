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
    /**
     * @param string $message
     * @return TestCase
     */
    protected function output(string $message): TestCase
    {
        fwrite(STDERR, $message . PHP_EOL);
        return $this;
    }

    /**
     * @return array
     */
    protected function getResponseBody(): array
    {
        $_body = $this->response->content();
        $body = json_decode($_body, true);

        return $body;
    }
}
