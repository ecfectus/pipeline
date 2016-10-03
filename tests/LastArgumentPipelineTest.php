<?php

namespace Ecfectus\Pipeline\Test;

use Ecfectus\Pipeline\LastArgumentPipeline;
use PHPUnit\Framework\TestCase;

class LastArgumentPipelineTest extends TestCase
{
    public function testWithOneArgument(){
        $pipeline = new LastArgumentPipeline();
        $pipeline->push(function($arg, callable $next){
            return $next($arg + 1);
        });
        $pipeline->push(function($arg, callable $next){
            return $next($arg + 1);
        });

        $this->assertEquals(2, $pipeline(0));
    }

    public function testWithMultipleArgument(){
        $pipeline = new LastArgumentPipeline();
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += 1;
            $arg2 += 1;
            $arg3 += 1;
            return $next($arg, $arg2, $arg3);
        });
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += 1;
            $arg2 += 1;
            $arg3 += 1;
            return $next($arg, $arg2, $arg3);
        });

        $this->assertEquals(4, $pipeline(0, 0, 2));
    }
}
