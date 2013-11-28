---
title: Coalescing operation in PHP (for default values)
slug: coalescing-operation-in-php-for-default-values
abstract: An example of the coalescing operation in PHP, useful for default values.
date: 27th Nov 2013
---

Over the past week or so I have been reading discussions on the [PHP internals](http://news.php.net/php.internals) mailing-list about proposed updates to what the ?: operator currently does.
If you are like me, you may not have even known that you could use the ternary operator (since 5.3) as a coalescing operator.
It however is a simple example of syntaxic sugar to cut down on code noise, allowing you to specify an alternative (default) value if the supplied variable is [falsey](http://php.net/manual/en/language.types.boolean.php).
In effect, assuming a null defined variable '$a', the examples below will all equate to the same result 'b'.

    $a = $a ?: 'b';
    if ( ! $a) $a = 'b';
    $a || $a = 'b';
    $a or $a = 'b';

However, issues arise when the variable has not already been declared, in such a case notice messages will be displayed.
This is due to attempting to use the non-existent variable out right, without checking with the 'is_set' or 'empty' functions first.
This is one of the key areas being addressed in the proposed update.
The examples below, more verbosely than desired, address this issue at present.


    $a = empty($a) ?: 'b';
    isset($a) && ! $a or $a = 'b';
    (isset($a) && ! $a) || $a = 'b';

In regard to the middle one, we are taking advantage of OR/AND's lower [operator precedence](http://php.net/manual/en/language.operators.precedence.php), removing the need for brackets (found in the last one).
I should point out however, that mixing the two can be a recipe for debug-hell, and it would be best practise to maintain expressing such statements using && and ||.