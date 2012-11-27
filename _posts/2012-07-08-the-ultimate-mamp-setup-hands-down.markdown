---
layout: post
title: The ultimate MAMP setup, hands down
---

I have been a loyal [MAMP Pro](http://www.mamp.info/en/mamp-pro/) user for many years, I fell in love with how easy it was to setup custom hosts, without the need to tweak the hosts file myself.
However, ever since I documented my [experiences](http://eddmann.com/posts/dnsmasq-your-local-development-dns/) with DNSMasq I have been intrested in how [this](http://davidwinter.me/articles/2011/06/18/simple-local-web-development-with-apache-and-dnsmasq/) article documented setting up a web-stack with Apache similar to Ruby user's [Pow](http://pow.cx/).
The ability to setup a new development site with only the creation of a new folder (i.e. a folder called test could be accessible from test.dev) appealed to me greatly.
Below is a step by step guide of how I setup my MAMP environment, full credit goes to the discussed approach, without it a small part of my heart would never have been filled.

### Apache and PHP

The first piece of the puzzle is to setup Apache, luckily for us Mac OSX (Lion) already comes preinstalled with an adequate Apache installation, all we have to do is enable it.
To go about achieving this we must first go to System Preferences, then Sharing and finally check Web Sharing.

How easy was that? The second piece is PHP, and again an installation is already provided with OSX (5.3.10 to be exact), all that is required is to load the PHP5 module into your Apache setup.
This can be achieved by uncommenting <span class="snippet">LoadModule php5_module libexec/apache2/libphp5</span> in your <span class="snippet">/etc/apache2/httpd.conf</span> file.

### Fancy some PHP 5.4?

If you feel ultra-cool and want to use the latest and greatest from PHP - such as traits and the beautiful JavaScript style array syntax, you can install PHP 5.4 using [Homebrew](http://mxcl.github.com/homebrew/) following the below intructions:

{% highlight bash %}
$ brew tap josegonzalez/homebrew-php
$ brew install php54
{% endhighlight %}

To enable PHP 5.4 for use with Apache you need to replace the uncommented PHP module (from the last step) with <span class="snippet">LoadModule php5_module /usr/local/Cellar/php54/5.4.9/libexec/apache2/libphp5.so</span>.

### MySQL

To install MySQL I have decided to skip all the hard work of compiling my own build and again let Homebrew do all the hard work.
Run the following commands to successfully setup your own personal MySQL installation.
Remember to follow the instructions Homebrew provides you, I personally also recommend you run the optional secure installation script once complete (even if it is just a development setup).

{% highlight bash %}
$ brew install mysql
$ # The folder version may vary
$ /usr/local/Cellar/mysql/5.5.20/bin/mysql_secure_installation
{% endhighlight %}

We must then make Apache aware that MySQL now has setup shop on your system.
To do this, run the following commands.

{% highlight bash %}
$ sudo mkdir /var/mysql
$ sudo ln -s /tmp/mysql.sock /var/mysql/mysql.sock
{% endhighlight %}

### DNSMasq and the magical part

Now that we have successfully setup are MAMP stack its time to add the magic sauce which makes this setup o' so much better.
I have already documented what DNSMasq is and how to set it up on a Linux distribution (Ubuntu) in a previous article so I will just skip the introductions and install it.
To install DNSMasq on Mac OSX I have decided to follow a similar process to MySQL and let Homebrew do all the hard work.

{% highlight bash %}
$ brew install dnsmasq
{% endhighlight %}

Once successfully downloaded/installed follow the onscreen instructions and copy the configuration file to <span class="snippet">/usr/local/etc/dnsmasq.conf</span>.
Before continuing on to the second stage of installation however, we need to tell DNSMasq (using the copied configuration file) that we want any address with a [TLD](http://en.wikipedia.org/wiki/Top-level_domain) of <span class="snippet">.dev</span> to loopback to our own machine.

{% highlight bash %}
address=/dev/127.0.0.1
listen-address=127.0.0.1
{% endhighlight %}

We then need to add our loopback address (127.0.0.1) as the first DNS record to our primary network adaptor.
We do this by going to System Preferences, then Network, once there we click Advanced and then DNS. 
Finally we can then add 127.0.0.1 as the first DNS record.

The last step is to setup the last development Apache Virtual Host you will hopefully ever have to look at.
Add the following Virtual Host information into your custom Apache configuration file, located at <span class="snippet">/etc/apache2/users/[your-username].conf</span>

{% highlight bash %}
NameVirtualHost *:80

<Directory "/Users/[your-username]/Sites/">
  Options Indexes MultiViews FollowSymLinks Includes
  AllowOverride All
  Order allow,deny
  Allow from all
</Directory>

<VirtualHost *:80>
  UseCanonicalName off
  VirtualDocumentRoot /Users/[your-username]/Sites/%1
</VirtualHost>
{% endhighlight %}

All that is needed now is to simply restart Apache by using the following command.

{% highlight bash %}
$ sudo apachectl restart
{% endhighlight %}

You can now add a new folder to your <span class="snippet">~/Sites</span> directory and without any other excess work visit the folder's name with the <span class="snippet">.dev</span> TLD prepended in your browser of choice.
I have added a simple function to my dotfiles which cuts out even this laborious task.

{% highlight bash %}
function newsite() {
  mkdir -p ~/Sites/$1
  echo "Hello, world..." > ~/Sites/$1/index.html
  echo "<?php phpinfo();" > ~/Sites/$1/info.php
}
{% endhighlight %}

Now that I have this setup I carn't imagine a world without it.
All the tedious work required in setting up a new project has now vanished!