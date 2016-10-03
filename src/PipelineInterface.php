<?php
/**
 * Created by PhpStorm.
 * User: leemason
 * Date: 02/10/16
 * Time: 11:20
 */

namespace Ecfectus\Pipeline;


/**
 * Interface PipelineInterface
 * @package Ecfectus\Pipeline
 */
interface PipelineInterface
{

    /**
     * Construct the pipeline with optional array of pipes.
     *
     * @param array $pipes
     */
    public function __construct(array $pipes = []);

    /**
     * Set the resolver for none callable callbacks.
     *
     * @param callable $resolver
     * @return PipelineInterface
     */
    public function setResolver(callable $resolver) : PipelineInterface;

    /**
     * Push a pipe onto the pipeline.
     *
     * @param null $pipe
     * @return PipelineInterface
     */
    public function push($pipe = null) : PipelineInterface;

    /**
     * Invoke the pipeline and sen through pipes.
     *
     * @return mixed
     */
    public function __invoke();

    /**
     * Returns true/false if the pipeline finished.
     *
     * @return bool
     */
    public function finished() : bool;

}