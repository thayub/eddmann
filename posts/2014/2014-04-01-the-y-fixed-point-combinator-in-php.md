---
title: The Y (Fixed-Point) Combinator in PHP
slug: the-y-fixed-point-combinator-in-php
abstract: An introduction to the Y-combinator, using PHP.
date: 1st Apr 2014
---

A combinator is a type of higher-order function that can be used to express functions without the explicit use of variables.
A fixed point is a value that is unchanged by a function, satisfying the equation which can be found [here](http://en.wikipedia.org/wiki/Fixed-point_combinator#Y_combinator).
Using the Y-combinator allows us to essentially convert non-recursive code into a recursive counterpart (without directly using named recursion or iteration).
To work it's magic the recursive function is computed as the fixed point of the non-recursive function.

You may be asking yourself why is this at all relevant in an imperative language such as PHP?
Well, with the introduction of Closures (since PHP 5.3) the language has slowly started to embrace many functional concepts.
One such concept however, still requires some work to correctly implement in-practice, that being recursive closures.
In a previous memorization [post](/posts/implementing-and-using-memoization-in-php/) I highlighted a factorial implementation using such an approach, requiring some PHP reference hackery to pass in the closure variable, as this would not typically be available in the function scope.
With a little research I stumbled upon the concept of Haskell's 'fix' function which is generally known by the name 'Y-combinator'.
I was keen to provide a thought-experiment into how this could be implemented in the PHP language along with some interesting example use-cases.

### Basic Implementation

Below is my first attempt at implementing the Y combinator in PHP, cheating a little by temporarily storing the fix-point function in a variable to remove code duplication.

~~~ .php
function Y($F)
{
    $x = function($f) use ($F)
    {
        return $F(function() use ($f)
        {
            return call_user_func_array($f($f), func_get_args());
        });
    };

    return $x($x);
}
~~~

This function can be then be applied to solve the Fibonacci sequence, as shown below.
As you can see the implementation provides us with the ability to reference the function by parameter instead of name (call-by-name), which in (typed) lambda calculus is not possible.

~~~ .php
$fibonacci = Y(function($fib)
{
    return function($n) use ($fib)
    {
        return $n > 1
            ? $fib($n - 1) + $fib($n - 2)
            : $n;
    };
});
~~~

### Adding Memorization

With the basic concept now implemented, we can simply expand on this example to include the ability to memorize function call results.
Providing an initial empty cache, we first check to see if the hashed function call arguments have already be processed in the past, if so we skip the function invocation step and return the answer.

~~~ .php
function YMemo($F, $cache = [])
{
    $x = function($f) use ($F, &$cache)
    {
        return $F(function() use ($f, &$cache)
        {
            $hash = md5(serialize(func_get_args()));

            if ( ! isset($cache[$hash])) {
                $cache[$hash] = call_user_func_array($f($f, $cache), func_get_args());
            }

            return $cache[$hash];
        });
    };

    return $x($x);
}
~~~

As the added memorization is an implementation detail, the user facing API has not changed and the function can again be expressed in the same manner as before (now with significant run-time speed increases).

~~~ .php
$fibonacci = YMemo(function($fib)
{
    return function($n) use ($fib)
    {
        return $n > 1
            ? $fib($n - 1) + $fib($n - 2)
            : $n;
    };
});
~~~

### Using Closure Bindings

Included more for it's athsetic appeal (syntactic sugar) we can take advantage of [Closure Bindings](http://www.php.net/manual/en/closure.bind.php) within PHP (since 5.4) to remove the need to explicitly pass in the fixed-point function.
Although clearly violating the properties of a true Y-combinator, we are instead able to now simply invoke '$this' with the supplied arguments, providing a more user-friendly implementation.

~~~ .php
function Yish($F)
{
    $x = function($f) use ($F)
    {
        return $F->bindTo(function() use ($f)
        {
            return call_user_func_array($f($f), func_get_args());
        });
    };

    return $x($x);
}
~~~

We can use the example of the Fibonacci sequence again, to this time make use of the closure bound implementation.

~~~ .php
$fibonacci = Yish(function($n)
{
    return $n > 1
        ? $this($n - 1) + $this($n - 2)
        : $n;
});
~~~

### Resources

- [Haskell - Fix and Recursion](http://en.wikibooks.org/wiki/Haskell/Fix_and_recursion)
- [Y-Combinator in PHP](http://php100.wordpress.com/2009/04/13/php-y-combinator/)
- [Fixed-point combinators in JavaScript](http://matt.might.net/articles/implementation-of-recursive-fixed-point-y-combinator-in-javascript-for-memoization/)