---
layout: post
title: Accessing PHP documentation using 'pman'
---

PHP has a ridiculous amout of in-built functions, even though I code in it daily I still get the surprise of finding a new one.
It wasn't until recently that I touched upon <span class="snippet">strip_tags</span>, saving me ample's amount of time having to write my own implementation.
My philosophy now is that if theres a function need you require, PHP most likely already has it.
In spite of this wealth of function goodness however, I hate having to load up a browser and visit [php.net](http://php.net/) to read the documentation (no matter how good it is).
I spend way too much time in the terminal now, and love the UNIX ethos of being able to run <span class="snippet">man 'command'</span> and quickly display documentation on a specific command.
What I want is the same capability but in regard to PHP functions - thankfully this need has been fulfilled in the form of 'pman'.

### Installation

Installing 'pman' is incredibly easy using PEAR, all you have to do is run the command below:

{% highlight bash %}
$ pear install doc.php.net/pman
{% endhighlight %}

Once the installation has successfully completed you can now access manual pages for PHP functions by calling <span class="snippet">pman</span> followed by the function name, for example:

{% highlight bash %}
$ pman strip_tags
{% endhighlight %}

### I'm feeling lucky

As an extra goody to get acquainted with as many functions as possible I have created a simple bash script, though a very crude implementation, which randomly selects a PHP manual page and displays it.

{% highlight bash %}
function rpman() {
  cd `pman -w`
  cd `ls | head -1`
  a=(*);
  func=$(echo ${a[$((RANDOM % ${#a[@]}))]} | 
         sed -E 's/([^0-9]).[0-9].gz/\1/')
  pman $func  
}
{% endhighlight %}