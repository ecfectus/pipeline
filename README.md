# Pipeline

[![Build Status](https://travis-ci.org/ecfectus/pipeline.svg?branch=master)](https://travis-ci.org/ecfectus/pipeline)

A Simple PHP 7 Pipeline class.

The ecfectus pipeline is an opinionated sequential runner which takes pipes, and runs the given value(s) through each one, returning the end result.

Unlike other pipelines which are strict on number of arguments, the ecfectus pipeline will accept and return whatever you give to it.

```php
$pipeline new Ecfectus\Pipeline\Pipeline();
$pipeline->push(function($arg, callable $next){
    return $next($arg + 1);
});
$result = $pipeline(1);//$result = 2

$pipeline new Ecfectus\Pipeline\Pipeline();
$pipeline->push(function($arg, $arg2, callable $next){
    return $next($arg + 1, $arg2);
});
$result = $pipeline(1,3);//$result = [2, 3]
```

If you give it 1 argument, that's what you get back, and the same is true for 6 arguments or more.

Whats more it will also tell you if the pipeline finished, or returned early from a pipe.

```php
$pipeline new Ecfectus\Pipeline\Pipeline();
$pipeline->push(function($arg, callable $next){
    return $next($arg);
});
$pipeline->push(function($arg, callable $next){
    return $arg;
});
$pipeline->push(function($arg, callable $next){
    return $next($arg + 1);
});
$result = $pipeline(0);//$result = 0
$pipeline->finished();// == false as pipe 2 returned a result, not $next();
```
