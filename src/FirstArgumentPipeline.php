<?php
/**
 * Created by PhpStorm.
 * User: leemason
 * Date: 03/10/16
 * Time: 17:22
 */

namespace Ecfectus\Pipeline;


class FirstArgumentPipeline extends Pipeline implements PipelineInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(...$arguments)
    {
        if($this->finished()){
            return $arguments[0];
        }
        return parent::__invoke(...$arguments);
    }
}