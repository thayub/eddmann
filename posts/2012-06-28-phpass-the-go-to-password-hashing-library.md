---
title: PHPass, the go-to password hashing library
slug: phpass-the-go-to-password-hashing-library
abstract: Password security is a highly discussed/misunderstood topic.
date: 28th June 2012
revised: 26th Nov 2013
---

It is becoming a scarily common occurence to read about [yet](http://www.guardian.co.uk/technology/us-news-blog/2012/jun/07/blogpost-eharmony-linkedin-hacked-leaked) [another](http://www.bbc.co.uk/news/technology-18338956) [batch](http://www.pcworld.com/article/257178/music_site_lastfm_joins_the_passwordleak_parade.html) of high-profile websites user's passwords being leaked online - for everyone's cracking pleasure.
Whats even more shocking is how poorly these sites are storing them.
In regard to LinkedIn, it turned out that they had just stored them as un-salted, plan 'ol MD5 hashes, that any Joe Bloggs could run a rainbow table over with extremely high success rates.
The tried and proven means of storing passwords in todays web applications is to create a hash from the user's input, and then compare any attempted authentication with this stored value.
The trouble and confusion arises however in how to actually get to this end result, there are so many 'wrong' ways to go about it.

### How should you be storing passwords then?

Reading through countless articles and experimenting with many implementations I have arrived at the conclusion that, as the title of this post clearly states, [PHPass](http://www.openwall.com/phpass/) is the way to go - of course if your programming in PHP that is.
PHPass provides you with the latest and greatest ideologies to deter malicious third-parties from successfully cracking your user's passwords.
I say deter as nothing is unbreakable, and your main aim as the developer is to make it as hard as possible for the attacker (hey, they love a challenge).

### What PHPass gives you

PHPass gives you the lethal combination of salting, key stretching and Bcrypt.
Salting is a relatively common method used in making the process of cracking your stored hashes harder.
Salts are most commonly unqiue (technically called a [nonce](http://en.wikipedia.org/wiki/Cryptographic_nonce)), or though they can be global, that are appendend/prepended to the given value making the produced output unqiue no matter what the input.
Randomness for these salts is generated from multiple factors, depending on the host system, for example on a UNIX system '/dev/urandom' can be used.

Salting prevents a few of the most widely exploited weaknesses in todays hashing functions.
Thease threats include the ability to use a pre-hased list (such as a rainbow table) on the subject, as well as the ability to reuse a cracked hash's password on another record with the same hash sequence.
I have spent many years implementing this method, both as a per-application and record basis.
In the last couple of developments I have introduced the concept of combining the two, as documented by Steve Gibson on the awesome Security Now podcast ([episode 358](http://www.grc.com/sn/sn-358.htm)).

Key streching was a new concept to me before I started to seriously put time into researching this subject.
At its most basic form, key stretching is the process of re-running a said action, in are case cypotgraphic function a certain amount of times before returning with the output.
Commonly used cryptographic hashing functions such as MD5, SHA1, SHA2 were not designed to be used for password hashing, but instead designed for speed.
What seemed like a complicated enough algorithm 10 years ago has now become an obsolete opinion due to [Moore's law](http://en.wikipedia.org/wiki/Moore's_law) working its magical powers.
These algorithms by themselves are just too easy for current hardware to crack (even with a salt), check out [this](http://www.troyhunt.com/2012/06/our-password-hashing-has-no-clothes.html) blog post to read more.
The way in which we solve this issue is by doing mutiple iterations (called key stretching) of the cryptographic function over the input before returning the output.
This slows down and adds complexity to the calculation process, which as a password hashing function is our goal.
You commonly only compute hashes in a typical web appliction at login and on signup, adding a few (micro)seconds to this process will not affect you, but increases the time it takes the cracker exponentially.

The final piece of the PHPass puzzle is [Bycrpt](http://en.wikipedia.org/wiki/Bcrypt).
Created in 1999 by Niels Provos and David Mazieres, it was designed from the ground up to be a password cryptographic hash function.
By this I mean that it was meant to be a technically slow, memory-expensive process (making a brute-force attack an extremely laborious task) - helping put Moore's law back in its place.
To help future proof the implementation it is adaptive and supports the use of key stretching.
This allows you to set the amount of iterations to do per hash, which can be defined per hash as the count is stored within the output.

### A simple example

There are many good examples of how to use PHPass already online (such as [here](http://sunnyis.me/blog/secure-passwords/)), so I will keep mine short and sweet.
You can simply hash a password with PHPass using the code snippet below.

~~~ .php
require_once('PasswordHash.php');

// a new phpass instance, providing the iteration count
// and if to use the in-built MD5 crypto or not
$phpass = new PasswordHash(8, FALSE);

$password = 'password1234';

$hash = $phpass->HashPassword($password);
~~~

Its even easier to compare hashes, using the following code snippet.

~~~ .php
$hash = '$2a$08$ezQB7LWGLPs3RtJLS9os5...';

$password = 'passsword1234';

if ($phpass->CheckPassword($password, $hash))
  echo 'We have found a match!';
~~~

I am an avid CodeIgniter user at present, and was happy to discover that a quick search on GitHub returned a very cool PHPass wrapper library (love the use of the '\_call' function).
The library can be found [here](http://github.com/segersjens/CodeIgniter-Phpass-Library).

The one and only point I hope you take away from this article is that if you are using PHP, use PHPass as your password hashing library!

### Update

Since the release of PHP 5.5 an even simpler set of functions have been added to the core (documentation found [here](http://php.net/manual/en/function.password-hash.php)), and it is recommended that you take advantage of these instead.
[Anthony Ferrara](http://blog.ircmaxell.com/), the creator of the update has also provided a library for backwards compatability (>= PHP 5.3.7) [here](http://github.com/ircmaxell/password_compat).