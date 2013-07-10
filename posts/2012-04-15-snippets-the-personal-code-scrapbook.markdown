---
title: Snippets, the personal code scrapbook
slug: snippets-the-personal-code-scrapbook
---

If you are a programmer it is almost a natural bi-product that you don't like typing the same thing more than once.
Logically it just doesn't compute well with you, isn't that what computers are for, to automate stuff.
Along with this, I’m sure you have (on more than one occasion) spent the time to find some code online/previous project, only to waste the same time again finding it for another project.

<img src="/posts/snippets-the-personal-code-scrapbook/snippets.png" class="centre" />

This is where *snippets* comes in, *snippets* is a small PHP/MySQL based web application that allows you to easily setup an online repository of the code you use the most.
This could be for personal use or maybe to aid a development team, the possibilities are endless.

Full-text search is provided, along with a tagging system to quickly locate the snippet you are looking for.
Along with this is a very basic security implementation, which requires the user to answer a random secret question (acquired from the database) upon any addition/alteration to a record.

### Technologies Used

During the development of this application I was able to experiment and implement the use of a host of technologies, including:

- [SlimPHP](http://www.slimframework.com/)
- [TWIG](http://twig.sensiolabs.org/)
- [PDO MySQL](http://php.net/manual/en/ref.pdo-mysql.php)
- [jQuery Tag-Input Plugin](http://xoxco.com/projects/code/tagsinput/)
- [Ace Editor](http://ace.ajax.org/)
- [Twitter Bootstrap](http://twitter.github.com/bootstrap/)

This was the first time that I had used the SlimPHP framework combined with TWIG templating in any real work context.
Thanks to the [Slim-Extras](https://github.com/codeguy/Slim-Extras) available on GitHub setup was a breeze and allowed me to take advantage of what TWIG has as a template engine.

### Come Get Some

If you are interested in having a lookie at what *snippets* can offer you, the Git repository is available for your forking pleasure at [GitHub](https://github.com/eddmann/snippets).
Along with this, my personal *snippets* collection can be found [here](http://snippets.eddmann.com), maybe you will find some you like in there to add to your own.

Happy code snipping!