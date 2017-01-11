<?php

namespace Combustion\StandardLib\Pipelines;

use InvalidArgumentException;

/**
 * Class InjectablePipeline
 * @package Combustion\StandardLib\Pipelines
 * @author Carlos Granados <cgranados@combustiongroup.com>
 */
class InjectablePipeline
{
    /**
     * @var callable[]
     */
    private $stages = [];

    /**
     * @var InjectableProcessor
     */
    private $processor;

    /**
     * Constructor.
     *
     * @param callable[]         $stages
     * @param InjectableProcessor $processor
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $stages = [], InjectableProcessor $processor = null)
    {
        foreach ($stages as $stage) {
            if (false === is_callable($stage)) {
                throw new InvalidArgumentException('All stages should be callable.');
            }
        }

        $this->stages       = $stages;
        $this->processor    = $processor ?: new InjectableProcessor();
    }

    /**
     * @inheritdoc
     */
    public function pipe(callable $stage)
    {
        $pipeline = clone $this;
        $pipeline->stages[] = $stage;

        return $pipeline;
    }

    /**
     * Process the payload.
     *
     * @param $payload
     * @param \Closure $postProcess
     * @return mixed
     */
    public function process($payload, \Closure $postProcess)
    {
        return $this->processor->process($this->stages, $payload, $postProcess);
    }

    /**
     * @inheritdoc
     */
    public function __invoke($payload, \Closure $postProcess)
    {
        return $this->process($payload, $postProcess);
    }
}
