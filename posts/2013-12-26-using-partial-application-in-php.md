---
title: Using Partial Application in PHP
slug: using-partial-application-in-php
abstract: Implementing a partial application function in PHP, with typical use-cases.
date: 26th Dec 2013
---

Partial function application is a common place feature found in many languages that lean towards the functional paradigm.
Unlike some functional concepts (monads) it can be simply explained as taking a function and binding argument values to one or more of its parameters, resulting in a new function.
Function argument size in computer science circles is described as its [arity](http://en.wikipedia.org/wiki/Arity), and through this process we are reducing the arity based on the partial application call.
Discussion of partial application typically brings up the related topic of [currying](http://en.wikipedia.org/wiki/Currying), which follows the stricter rule of transforming a multiple argument function into a chain of single argument calls.
This is useful as it helps simplify the study of functions in theoretical computer sciences, such as lambda calculus.
Currying however is not that useful in general-purpose languages, dissimilar to Haskell which at its core only supports the mathematical notion of single argument functions.
Using a combination of syntactic sugar and currying, the language is able to give the misguided impression of multi-argument calls, when in-fact it is just a chain of single argument curried calls.

Below is a simple implementation of a partial application function in PHP, taking advantage of its first-class function and closure support.
The solution I was able to come up is very similar to the one documented [here](http://allthingsphp.blogspot.co.uk/2012/02/currying-vs-partial-application.html), and will fortunately be more clearly expressed with the inclusion of [variadic function syntax](https://wiki.php.net/rfc/variadics) in 5.6.

~~~ .php
function partial(/* $func, $args... */)
{
    $args = func_get_args();
    $func = array_shift($args);

    return function() use ($func, $args)
    {
        return call_user_func_array($func, array_merge($args, func_get_args()));
    };
}
~~~

Using the above implementation many very useful functions based on pre-existing function behavior can be created.
The example below defines an addition function which with partial application can be used to produce increment and decrement functions.

~~~ .php
function add($a, $b)
{
    return $a + $b;
}

$inc = partial('add', 1);
$dec = partial('add', -1);
~~~

In a similar manner a multiplication function can be defined with double and half functions created from it with partial application.

~~~ .php
function times($a, $b)
{
    return $a * $b;
}

$double = partial('times', 2);
$half = partial('times', .5);
~~~

These four new methods can then be used in conjunction in the following contrived example.

~~~ .php
$inc(4) + $half(10); // 10
$dec(6) + $double(2.5); // 10
~~~

The earlier discussed post also details another example were partial application can come in very useful, when using the date function.
On a per-project basis I typically create functions which fill in the date format of the function, all of which can now be taken care of with the generalised partial function.

~~~ .php
$rssFormat = partial('date', DATE_RSS);
$rssFormat(1388534400); // Wed, 01 Jan 2014 00:00:00 +0000
~~~

### Resources

- [Currying vs. Partial Application](http://allthingsphp.blogspot.co.uk/2012/02/currying-vs-partial-application.html)
- [Partial Application in JavaScript](http://benalman.com/news/2012/09/partial-application-in-javascript/)