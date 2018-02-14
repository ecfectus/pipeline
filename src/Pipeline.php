<?php
namespace Ecfectus\Pipeline;

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

        $this->pipeline = $pipes;

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
        $this->pipeline[] = $pipe;
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
        $pipe = $resolver(array_shift($this->pipeline));
        $arguments[] = $this;
        return $pipe(...$arguments);
    }

    /**
     * @inheritDoc
     */
    public function finished() : bool
    {
        return empty($this->pipeline);
    }


}