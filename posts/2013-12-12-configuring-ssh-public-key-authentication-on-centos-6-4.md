---
title: Configuring SSH Public Key Authentication on CentOS 6.4
slug: configuring-ssh-public-key-authentication-on-centos-6-4
abstract: Simple guide on how to setup password-less SSH login on CentOS 6.4.
date: 12th Dec 2013
---

Having to use password authentication each time you wish to access your server can be a serious pain.
Not only does it require extra keystrokes, it is also less secure and far more susceptible to successful brute-force attacks.
Enter public-key authentication, were instead you use asymmetric cryptography.
The first thing to do is generate a key-pair on your client machine, you can optional provide a passphrase to unlock the private key if you so wish.

~~~ .bash
$ ssh-keygen -q -t rsa -C "your@email.com"
~~~

We now need to add the clients public-key to the list of authorised keys for the server's specifed user.

~~~ .bash
$ cat ~/.ssh/id_rsa.pub | ssh user@hostname "cat >> ~/.ssh/authorized_keys"
~~~

Once this has been successfully copied across we just need to enable the daemon to use the new form of authentication.
This will be the last time you will have to authenticate via password.

~~~ .bash
$ ssh user@hostname
$ sudo sed -i "s/^\#RSAAuthentication.*$/RSAAuthentication yes/g" /etc/ssh/sshd_config
$ sudo sed -i "s/^\#PubkeyAuthentication.*$/PubkeyAuthentication yes/g" /etc/ssh/sshd_config
$ sudo /etc/init.d/sshd restart
~~~