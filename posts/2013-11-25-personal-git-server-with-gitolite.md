---
title: Personal Git Server with Gitolite
slug: personal-git-server-with-gitolite
abstract: Create your own personal centralised Git server.
date: 25th Nov 2013
---

Github and Bitbucket are great, however, there may come a time when you wish to setup a personal Git server.
There are many reason for this, you may legally not be permitted to host the repository externally, or you want to have more control over access privileges.
[Gitolite](http://gitolite.com/gitolite/index.html) is here to help remedy this desire, allowing you to simply setup Git hosting on a central server with fine-grained access control capabilities.

In this post I will take you through the steps required to setup Gitolite on a base CentOS 6.4 installation.
I will assume you have such an installation (or equivalent) available throughout the post.

### Installation

The first step is to add the [EPEL](http://fedoraproject.org/wiki/EPEL) repository (if not already present), along with installing Git and Gitolite using YUM.
Alternativity, you are able to install Gitolite from [source](http://github.com/sitaramc/gitolite), instructions of which can be found there.

    $ sudo rpm -Uvh http://download.fedoraproject.org/pub/epel/6/x86_64/epel-release-6-8.noarch.rpm
    $ sudo yum install -y git gitolite3

Gitolite intuitively uses a Git repository itself to handle the hosted repositories and user accounts.
We will be assuming that you wish to use your current logged-in account for intial management of this repoistory - and as such we must create an SSH key-pair (if not already present).
We then copy over the public key to a location accessible to a new account we will be creating next.

    $ ssh-keygen -q -t rsa -C "your@email.com"
    $ cp ~/.ssh/id_rsa.pub /tmp/server.pub

It is best practise to setup and access your Gitolite server from its own dedicated locked-down user account.
The 'gitolite setup' command below will add the copied over public key to the list of users able to manage the admin repository.

    $ useradd -U git
    $ sudo su - git
    $ gitolite setup -pk /tmp/server.pub
    $ rm /tmp/server.pub
    $ exit

### Usage

With the inital setup now complete, from the previously used account we can list the available repositories and clone the admin repository.

    $ ssh git@localhost info
    $ git clone git@localhost:gitolite-admin.git

We are now able to manage the server's repositories and user access privileges from 'conf/gitolite.conf'.
Adding and then commit/pushing the following change will create a new empty repository called 'helloworld' which everyone has read/write access to.

    repo helloworld
        RW+ = @all

Finally you are then able to follow the same commit/push routine when adding user's public keys to the 'keydir' directory.
Take note that the name of the public key will be how your reference that user in the configuration file (i.e. bob.pub would be @bob).

### Vagrant Demo

To test this setup I used Vagrant and a simple shell-script provisioner, I thought it would be useful to provide it.
One note on the provisioner file, due to being run as 'root' it is a necessity to switch to the correct account per-each required command.

- [Vagrantfile](/uploads/personal-git-server-with-gitolite/Vagrantfile) - Vagrant configuration file
- [bootstrap.sh](/uploads/personal-git-server-with-gitolite/bootstrap.sh) - Shell-script provisioner