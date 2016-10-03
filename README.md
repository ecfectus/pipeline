# Pipeline

[![Build Status](https://travis-ci.org/ecfectus/pipeline.svg?branch=master)](https://travis-ci.org/ecfectus/pipeline)

A Simple PHP 7 Pipeline class.

The ecfectus pipeline is an un-opinionated sequential runner which takes pipes, and runs the given value(s) through each one, returning the end result.

Unlike other pipelines which are strict on number of arguments, the ecfectus pipeline will accept and return whatever you give to it.

If you give it 1 argument, that's what you get back, and the same is true for 6 arguments or more.

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

By design passed pipes MUST be `callable` and they are invoked in sequence.
However that's not set in stone, you could if needed set your own resolver to resolve each pipe, perfect when using ioc containers, or to provide app specific syntax like `Class@method`.

```php
$pipeline new Ecfectus\Pipeline\Pipeline();
$pipeline->setResolver(function($pipe){
    if(is_string($pipe)){
        //parse string, or fetch from ioc container
        $pipe = $someContainer->get($pipe);
    }
    if(is_callable($pipe)){
        return $pipe;
    }
    throw new InvalidArgumentException('Whoah, we could find that pipe!');
});
```

## First or Last Argument Pipeline

As described above what you give the pipeline, and then return from the pipes is the result given to you when completed.

However this flexibility isn't always desired, for example if using the pipeline as a middleware runner with request and response objects, you only want back the response.

This is where the `FirstArgumentPipeline` or `LastArgumentPipeline` classes come in.

```php
$pipeline new Ecfectus\Pipeline\FirstArgumentPipeline();
$pipeline->push(function($arg, $arg2, callable $next){
    return $next($arg + 1, $arg2);
});
$result = $pipeline(0,0);//$result = 1

$pipeline new Ecfectus\Pipeline\LastArgumentPipeline();
$pipeline->push(function($arg, $arg2, callable $next){
    return $next($arg + 1, $arg2 + 2);
});
$result = $pipeline(0,0);//$result = 2
```