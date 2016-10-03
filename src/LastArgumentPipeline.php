<?php
/**
 * Created by PhpStorm.
 * User: leemason
 * Date: 03/10/16
 * Time: 17:22
 */

namespace Ecfectus\Pipeline;


class LastArgumentPipeline extends Pipeline implements PipelineInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(...$arguments)
    {
        if($this->finished()){
            return array_pop($arguments);
        }
        return parent::__invoke(...$arguments);
    }
}