<?php
namespace Ecfectus\Pipeline;

use Ds\Queue;

class Pipeline implements PipelineInterface{

    /**
     * @var Queue|null
     */
    protected $pipeline = null;

    /**
     * @var null
     */
    protected $resolver = null;

    /**
     * @inheritDoc
     */
    public function __construct(array $pipes = []){

        $this->pipeline = new Queue($pipes);

        $this->resolver = function(callable $pipe){
            return $pipe;
        };
    }

    /**
     * @inheritDoc
     */
    public function setResolver(callable $resolver) : PipelineInterface
    {
        $this->resolver = $resolver;
        return $this;
    }

    /**
     * @inhertDoc
     */
    public function push($pipe = null) : PipelineInterface
    {
        $this->pipeline->push($pipe);
        return $this;
    }

    /**
     * @inhertDoc
     */
    public function __invoke(...$arguments)
    {
        if($this->finished()){
            return (count($arguments) == 1) ? $arguments[0] : $arguments;
        }
        $resolver = $this->resolver;
        $pipe = $resolver($this->pipeline->pop());
        $arguments[] = $this;
        return $pipe(...$arguments);
    }

    /**
     * @inheritDoc
     */
    public function finished() : bool
    {
        return $this->pipeline->isEmpty();
    }


}