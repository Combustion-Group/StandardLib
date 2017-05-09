<?php

namespace Combustion\StandardLib\Services\Data\StandardGenerator;

use Symfony\Component\Process\Process;
use Illuminate\Contracts\Filesystem\Filesystem;
use Combustion\StandardLib\Traits\ValidatesConfig;
use Combustion\StandardLib\Services\Data\Exceptions\CompilationException;
use Combustion\StandardLib\Services\Data\StandardGenerator\Structs\ModelSpecification;

/**
 * Class Compiler
 *
 * @package Combustion\StandardLib\Services\Data\ModelGenerator
 * @author  Carlos Granados <cgranados@combustiongroup.com>
 */
class Compiler
{
    use ValidatesConfig;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var array
     */
    private $config;

    /**
     * Compiler constructor.
     *
     * @param array $config
     * @param Filesystem $filesystem
     */
    public function __construct(array $config, Filesystem $filesystem)
    {
        $this->filesystem   = $filesystem;
        $this->config       = $this->validateConfig($config);
    }

    /**
     * @return array
     */
    public function getRequiredConfig() : array
    {
        return ['temp_path'];
    }

    /**
     * @param ModelSpecification $specification
     * @return string
     * @throws CompilationException
     */
    public function run(ModelSpecification $specification) : string
    {
        $command = $this->compile($specification);

        $process = new Process($command);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new CompilationException("Failed to compile model from migration. Compiler Output: {$process->getOutput()}");
        }
    }

    /**
     * @param ModelSpecification $modelSpecification
     * @return string
     */
    private function compile(ModelSpecification $modelSpecification) : string
    {
        $command = "\"{$modelSpecification->getNamespace()}\" \"{$modelSpecification->getName()}\" \"{$modelSpecification->getAuthorName()}\" \"{$modelSpecification->getAuthorEmail()}\"";

        foreach ($modelSpecification->getColumns() as $type => $name) {
            $command = "{$command} {$type} {$name}";
        }

        return $command;
    }
}
