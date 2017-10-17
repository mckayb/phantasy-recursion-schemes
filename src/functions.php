<?php declare(strict_types=1);

namespace Phantasy\Recursion;

function curry(callable $callable)
{
    $ref = new \ReflectionFunction($callable);
    $numParams = $ref->getNumberOfRequiredParameters();

    $recurseFunc = function (...$args) use ($callable, $numParams, &$recurseFunc) {
        if (count($args) >= $numParams) {
            return call_user_func_array($callable, $args);
        } else {
            return function (...$args2) use ($args, &$recurseFunc) {
                return $recurseFunc(...array_merge($args, $args2));
            };
        }
    };

    return $recurseFunc;
}

function map(...$args)
{
    $map = curry(
        function (callable $f, $x) {
            if (is_callable([$x, 'map'])) {
                return call_user_func([$x, 'map'], $f);
            }
        }
    );
    return $map(...$args);
}

function cata(...$args)
{
    $cata = curry(function (callable $f, $xs) {
        return $f(map(cata($f), $xs));
    });

    return $cata(...$args);
}

function ana(...$args)
{
    $ana = curry(function (callable $f, $x) {
        return map(ana($f), $f($x));
    });

    return $ana(...$args);
}

function hylo(...$args)
{
    $hylo = curry(function (callable $f, callable $g, $x) {
        return $f(map(hylo($f, $g), $g($x)));
    });

    return $hylo(...$args);
}
