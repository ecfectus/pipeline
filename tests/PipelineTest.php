<?php

namespace Ecfectus\Pipeline\Test;

use Ecfectus\Pipeline\Pipeline;
use PHPUnit\Framework\TestCase;

class PipelineTest extends TestCase
{
    public function testWithOneArgument(){
        $pipeline = new Pipeline();
        $pipeline->push(function($arg, callable $next){
            return $next($arg + 1);
        });
        $pipeline->push(function($arg, callable $next){
            return $next($arg + 1);
        });

        $this->assertEquals(2, $pipeline(0));
    }

    public function testWithMultipleArgument(){
        $pipeline = new Pipeline();
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

        $this->assertEquals([2, 2, 2], $pipeline(0, 0, 0));
    }

    public function testWithMultipleArgumentReturningOne(){
        $pipeline = new Pipeline();
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += 1;
            $arg2 += 1;
            $arg3 += 1;
            return $next($arg, $arg2, $arg3);
        });
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += ($arg2 + $arg3);
            return $arg;
        });

        $this->assertEquals(3, $pipeline(0, 0, 0));
    }

    public function testReturningEarly(){
        $pipeline = new Pipeline();
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += 1;
            $arg2 += 1;
            $arg3 += 1;
            return $next($arg, $arg2, $arg3);
        });
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            return $arg;
        });
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += 1;
            $arg2 += 1;
            $arg3 += 1;
            return $next($arg, $arg2, $arg3);
        });

        $this->assertEquals(1, $pipeline(0, 0, 0));
    }

    public function testReturningEarlyFinishedStatus(){
        $pipeline = new Pipeline();
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += 1;
            $arg2 += 1;
            $arg3 += 1;
            return $next($arg, $arg2, $arg3);
        });
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            return $arg;
        });
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            $arg += 1;
            $arg2 += 1;
            $arg3 += 1;
            return $next($arg, $arg2, $arg3);
        });

        $result = $pipeline(0, 0, 0);

        $this->assertFalse($pipeline->finished());
    }

    public function testCompletedFinishedStatus(){
        $pipeline = new Pipeline();
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

        $result = $pipeline(0, 0, 0);

        $this->assertTrue($pipeline->finished());
    }

    /**
     * @expectedException TypeError
     */
    public function testThrowsTypeErrorWithNoneCallable(){
        $pipeline = new Pipeline();
        $pipeline->push('astring');
        $result = $pipeline(0);
    }

    /**
     * @expectedException Exception
     */
    public function testThrowsExceptionFromWithinPipe(){
        $pipeline = new Pipeline();
        $pipeline->push(function($arg, $arg2, $arg3, callable $next){
            throw new \Exception();
        });

        $result = $pipeline(0, 0, 0);
    }

    public function testSettingCustomResolver(){
        $pipeline = new Pipeline();
        $pipeline->push('testing');

        $pipeline->setResolver(function($pipe){
            if($pipe == 'testing'){
                return function($arg, $arg2, $arg3, callable $next){
                    return 1;
                };
            }
        });

        $result = $pipeline(0, 0, 0);

        $this->assertEquals(1, $result);
    }
}
