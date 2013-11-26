---
title: Accessing PHP documentation using 'pman'
slug: accessing-php-documentation-using-pman
abstract: Super quick access to the PHP documentation.
date: 11th Sep 2012
---

PHP has a ridiculous amout of in-built functions, even though I code in it daily I still get the surprise of finding a new one.
It was not until recently that I touched upon 'strip_tags', saving me amples amount of time having to write my own implementation.
My philosophy now is that if theres a function need you require, PHP most likely already has it.
In spite of this wealth of function goodness however, I hate having to load up a browser and visit [php.net](http://php.net/) to read the documentation (no matter how good it is).
I spend way too much time in the terminal now, and love the UNIX ethos of being able to run 'man command' and quickly display documentation on a specific command.
What I want is the same capability but in regard to PHP functions - thankfully this need has been fulfilled in the form of 'pman'.

### Installation

Installing 'pman' is incredibly easy using PEAR, all you have to do is run the command below:

    $ pear install doc.php.net/pman

Once the installation has successfully completed you can now access manual pages for PHP functions by calling 'pman' followed by the function name, for example:

    $ pman strip_tags

### I'm feeling lucky

As an extra goody to get acquainted with as many functions as possible I have created a simple bash script, though a very crude implementation, which randomly selects a PHP manual page and displays it.

    function rpman() {
      cd `pman -w`
      cd `ls | head -1`
      a=(*);
      func=$(echo ${a[$((RANDOM % ${#a[@]}))]} |
             sed -E 's/([^0-9]).[0-9].gz/\1/')
      pman $func
    }