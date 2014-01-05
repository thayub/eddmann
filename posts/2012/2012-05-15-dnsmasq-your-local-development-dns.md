---
title: DNSMasq, your local development DNS
slug: dnsmasq-your-local-development-dns
abstract: Local development can be a pain, checkout DNSMasq.
date: 15th May 2012
---

Setting up a single-user web development environment is easy.
One-click (OS)AMP installers do all the work and internal domains are all stored in one easy to reach host file.
However, expanding this to a multi-user development team, all of which needing access to the same resources, is a whole different beast.
On-top of this, they may require access from multiple devices, some of which may not include a host file, such as a mobile phone.
A common solution to this problem is to pass around host files (which encompass all of the development domains used) and hope that everyone is in sync.
In the case of devices that do not have a readily accessable host file, uploading externally accessible builds or providing an alternative gateway into the development ecosystem is one way to pursue a fix.
These methods do not feel right to me, far too many variables and extra work for my liking.
An even better solution, which will get rid of all the overhead, is to setup your own local DNS and start living like its a single-user environment again.

### Ladies and gentleman, DNSMasq

To setup your own local DNS you can spend hours configuring BIND, but nothing beats the ease and maintainability of DNSMasq.
DNSMasq is a lighweight DNS, DHCP and TFTP server provider and is ideal for this case-study.
In a couple of return-key-hits from inside your package manager of choice you will have setup a local DNS server for the whole development team to access, keeping all those pesky local domains in one host file, the way it should be.

### Installation

I will be installing and configuring a DNSMasq setup on a Ubuntu Server distrubtion, but installation on other setups should be fairly similar.

~~~ .bash
$ sudo apt-get install dnsmasq
~~~

Upon successful installation you may be greeted with a unfriendly 'port 53 already in use' message.
To resolve this issue use the following commands; first checking which process is already using port 53 and then killing that process by its id.

~~~ .bash
$ sudo netstat -anlp | grep -w LISTEN
$ sudo kill [process-id]
~~~

The last step is to add all your local development domains to the host file found at '/etc/hosts'.
Once all domains have been fully imported the final command that needs to be run is to restart DNSMasq.

~~~ .bash
$ sudo /etc/init.d/dnsmasq restart
~~~

To access your newly created DNS server, you can either individually add the servers IP address to each computers DNS network configuration or alternatively, point your routers DNS record to the IP address.

### An alternative option...

If your feeling extra hardcore and do not wish to install another software dependency on your system, you may wish to consider flashing your router with [DD-WRT](http://www.dd-wrt.com/) (if your router supports it) and allow hardware to do the work.
Setting up DNSMasq on an applicable DD-WRT installation is as simple as adding the local domain entries into the 'Additonal DNSMasq Options' textarea in the administration panel.

<figure>
    <figcaption>Below is an screenshot of an example DD-WRT DNSMasq configuration</figcaption>
    <img class="shadow" src="/uploads/dnsmasq-your-local-development-dns/dd-wrt.png" alt="DD-WRT" />
</figure>

### An even more awesome idea

Instead of having to add an extra entry for each development site, which can be a chore, we have the chance to make it even easier.
My regular naming convention for local development sites is to use the descriptive '.dev' TLD.
This naming scheme allows me to easily distinguish between development and live builds.
Lucky for us we have the chance to exploit the fact that '.dev' is an unused TLD.
With DNSMasq we are able to easily setup a TLD wildcard which will check/match any TLD we pass through the DNS and forward it to are defined location.
As a consequence any URL request sent with a '.dev' TLD can be directed to my local development server for Apache to happily respond to.
For setup on a software installation you are required to add the following line into your 'dnsconfig.conf'.

~~~ .bash
$ sudo "address=/dev/[dev-sever-ip]" >> /etc/dnsmasq.etc
~~~

Or in the case of DD-WRT, adding the above configuration setting to the 'Additonal DNSMasq Options' textarea in the administration panel.

### Resources

* [DNSMasq - Offical Site](http://www.thekelleys.org.uk/dnsmasq/doc.html)
* [Simple local web development with Apache and DNSMasq](http://davidwinter.me/articles/2011/06/18/simple-local-web-development-with-apache-and-dnsmasq/)
* [DD-WRT - Offical Site](http://www.dd-wrt.com/site/index)
* [DNSMasq as DD-WRT DHCP Server](http://www.dd-wrt.com/wiki/index.php/DNSMasq_as_DHCP_server)