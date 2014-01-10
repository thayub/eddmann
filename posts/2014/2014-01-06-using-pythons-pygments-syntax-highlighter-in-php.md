---
title: Using Python's Pygments Syntax Highlighter in PHP
slug: using-pythons-pygments-syntax-highlighter-in-php
abstract: The best syntax-highlighting library, hands-down.
date: 6th Jan 2014
---

Having a website that is heavily software-development based, one important aspect that can not be overlooked is well presented code examples.
Speaking on the importance of editor syntax highlighting in the [previous episode](http://threedevsandamaybe.com/posts/exploring-text-source-editors-and-ides/) of our podcast, this attribute transcends over to aid in the readability of code online.
Fortunately, there are many options to choose from, whether it is storing the code snippets in an embedded [Gists](http://gist.github.com/) or using the front-end based [highlight.js](http://highlightjs.org/) or [Google Code Prettify](http://code.google.com/p/google-code-prettify/).
One benefit that greatly simpled the publishing process when using a front-end based solution was that you could simply parse the Markdown file (maybe with a class language type-hint), and leave all the hard work for the client's browser.
As we all know, we have very little control over the viewing experience per user, and as I have started to post more frequently, cracks began to appear in the syntax highlighters I had been using.
However, when looking for a solution, one had been staring me straight in the face all this time, that being [Pygments](http://pygments.org/).

### Enter Pygments

I was first introduced to Pygments through my time using [Jekyll](http://jekyllrb.com/) a couple of years ago, since then however, due to limited demands in this area I stuck with highlight.js to perform my syntax highlighting.
This had worked well until recently, when a couple of shell scripts and Java enumerated type declarations had slipped it up.
If this is your first introduction to Pygments it is written in Python, and while I do like the language a lot I felt it maybe over-kill to move my blogging platform over to Python just for syntax highlighting (though, it was not out of the question).
As a result, I decided to see if there was anything on-par in the PHP landscape, a [Packagist](http://packagist.org/) search returned a few hopeful results, though the output was not comparable.
I could now see why the Ruby-based Jekyll project had gone to the effort to depend on the Python-based Pygments project to handle code syntax highlighting.
Looking deeper into Pygments success, the questions were answered by the attention paid to supporting new languages and edge-cases within the languages I was interested in using it for.
Installation of Pygments can be simply achieved by using the [EasyInstall](http://pypi.python.org/pypi/setuptools) package manager, or on a CentOS installation, via YUM.

~~~ .bash
$ sudo easy_install Pygments # EasyInstall
$ sudo yum install python-pygments # CentOS
~~~

### Bridging the Python and PHP Divide

Knowing now that I would only be truly happy with Pygments, my best option was to write a simple wrapper over the provided command-line interface, which would be the less obtrusive to my PHP application.
Making this decision now meant that I would have to handle the processing of posts code-snippets before supplying the Markdown parser with the files content.
Fortunately, before updating my blog I had decided to use the [Markdown Extra](http://michelf.ca/projects/php-markdown/extra/) parser produced by Michel Fortin, which allowed me to enclose code snippets in [fenced blocks](http://michelf.ca/projects/php-markdown/extra/#fenced-code-blocks).
I had also added the extra restraint of declaring these blocks with three tilde characters and a class language type-hint (~~~ .example).
This decision proved very useful when producing the example implementation below, though, it can be easily expanded to cater for more code-block declarations.

~~~ .php
function pygments($post)
{
    return preg_replace_callback('/~~~[\s]*\.([a-z]+)\n(.*?)\n~~~/is', function($match)
    {
        list($orig, $lang, $code) = $match;

        $proc = proc_open(
            'pygmentize -f html -O style=default,encoding=utf-8,startinline -l ' . $lang,
            [ [ 'pipe', 'r' ], [ 'pipe', 'w' ] /* ignore stderr */ ],
            $pipes
        );

        fwrite($pipes[0], $code);
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        return (proc_close($proc))
            ? $orig
            : $output;
    }, $post);
}
~~~

Coupled with the decision to only handle a defined code-block definition, I also made the assumption that the 'pygmentize' command is present in the user's path (this can be easily altered to).
Using the 'proc_open' function supplied by PHP I was able to begin a child-process which used the defined STDIN, STDOUT and STDERR descriptor pipes I supplied.
I decided to omit the STDERR pipe as I could pick up on an error occurring (regardless of the exact reason) when closing the process using 'proc_close'.
In the case of an error (a none zero returned value) I would simply return the original content, for the Markdown parser to process accordingly.
Within the 'pygmentize' command I supplied the option for HTML and default-styling/UTF-8 output.
The 'startinline' option removes the requirement to define a PHP start tag when displaying PHP syntax highlighted code-blocks.
The 'default' styling was intentionally defined to provide easier setup, discussed in the next section.

### Simple Styling

The are many styling and output options present in Pygments, and I would strongly recommend you check out the documentation.
Custom styles are typically installed in the same manner as the library itself, using EasyInstall.
However, there are many built-in styles which rely on the output generated from the 'default' style option, my favorite being [Zenburn](http://slinky.imukuppi.org/zenburnpage/).
These stylesheets can be generated using the 'pygmentize' command itself, but to make it super simple [this](http://github.com/richleland/pygments-css) GitHub repository provides them preprocessed.
If you have not already got a style in mind you can check out [this](http://igniteflow.com/pygments/themes/) page which neatly displays examples of each of the styles present.

### Resources

- [highlight.js](http://highlightjs.org/)
- [Google Code Prettify](http://code.google.com/p/google-code-prettify/)
- [Pygments](http://pygments.org/)
- [Packagist](http://packagist.org/)
- [Markdown Extra](http://michelf.ca/projects/php-markdown/extra/)
- [Proc_Open: Communicate with the Outside World](http://www.sitepoint.com/proc-open-communicate-with-the-outside-world/)
- [PHP Documentation: proc_open](http://www.php.net/manual/en/function.proc-open.php)
- [Pygments CSS Styles](http://github.com/richleland/pygments-css)
- [Pygments Style Previews](http://igniteflow.com/pygments/themes/)