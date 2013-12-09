---
title: Open external links in a new window using JavaScript
slug: open-external-links-in-a-new-window-using-javascript
abstract: Small, useful snippet in raw JavaScript and jQuery.
date: 9th Dec 2013
---

It is good practise to open external links in a new window, however, it can be abit tedious having to remember to include 'target="_blank"', especially in Markdown.
To get around this I have incorporated a simple raw JavaScript solution, which can be found below.

~~~ .javascript
for (var links = document.getElementsByTagName('a'), i = 0, l = links.length; i < l; i++) {
    var link = links[i];
    if (link.getAttribute('href') && link.hostname !== location.hostname)
        link.target = '_blank';
}
~~~

If you have access to jQuery on the page then a more elegant solution presented below would be more appealing.

~~~ .javascript
$('a[href^="http"]').not('[href*="' + location.hostname + '"]').attr('target', '_blank');
~~~