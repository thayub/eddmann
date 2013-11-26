---
title: An alternative to 'var_dump' in PHP
slug: an-alternative-to-var-dump-in-php
abstract: Used all the time, so lets make it better.
date: 10th Sep 2012
---

Whilst working with PHP, I seem to use 'var\_dump' a ridiculous amount, its a debugging must-have.
However, it does fall short in a few ways, especially in how it does not take into consideration that the function is almost always displayed in a HTML page - switching to the page's source can become a pain.
Due to the pitfalls a host of projects such as [Krumo](http://krumo.sourceforge.net/) and [Kint](http://raveren.github.io/kint/) have cropped up to cater for these needs.
As well as these projects, if you have [XDebug](http://xdebug.org/) installed it will replace the default 'var\_dump' function with its own implementation that outputs the information with well-needed styling.
For me though, I do not need all bells n' whistles that these provide, my base requirements are:

* Better presentation on a HTML page, no viewing of source necessarily
* Output of the Page, Class, Function, Line that called the function
* Ability to end script execution immediately after outputting the information (for easier debugging)

### The code...

So as a result of these requirements I created the two simple functions below.
I am sure there are many similar implementations available online, but these two are serving me well.

    function dump()
    {
      $args = func_get_args();

      echo "\n<pre style=\"border:1px solid #ccc;padding:10px;" .
           "margin:10px;font:14px courier;background:whitesmoke;" .
           "display:block;border-radius:4px;\">\n";

      $trace = debug_backtrace(false);
      $offset = (@$trace[2]['function'] === 'dump_d') ? 2 : 0;

      echo "<span style=\"color:red\">" .
           @$trace[1+$offset]['class'] . "</span>:" .
           "<span style=\"color:blue;\">" .
           @$trace[1+$offset]['function'] . "</span>:" .
           @$trace[0+$offset]['line'] . " " .
           "<span style=\"color:green;\">" .
           @$trace[0+$offset]['file'] . "</span>\n";

      if ( ! empty($args)) {
        call_user_func_array('var_dump', $args);
      }

      echo "</pre>\n";
    }

    function dump_d()
    {
      call_user_func_array('dump', func_get_args());
      die();
    }

### Resources

* [A Gist of the above implementation](http://gist.github.com/3692379)
* [Kint](http://raveren.github.io/kint/)
* [Krumo](http://krumo.sourceforge.net/)
* [XDebug](http://xdebug.org/)