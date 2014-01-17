---
title: Transferring Files using SSH and SCP
slug: transferring-files-using-ssh-and-scp
abstract: Simple, reference post about handling file transfers using SSH and SCP.
date: 17th Jan 2014
---

When you are managing multiple servers on a daily basis it pays off to invest some time in picking up a few techniques to get jobs finished quicker.
Below are a couple of the commands I use frequently when handling files and directories via SSH and SCP.

### SSH

I am sure you are familiar with SSH (Secure Shell), I spend most of my day-to-day work-life with a remote connection open.
Thanks to the [Unix philosophy](http://en.wikipedia.org/wiki/Unix_philosophy), combined with other tools (commands) you are able to expand the single focused functionality of SSH.

#### Execute Remote Commands

Using SSH you are able to supply an arbitrary command you wish to execute on a remote server.
Below are a couple of useful commands I use to monitor multiple facets of the server.
Adding these commands via an alias in your dotfiles, could shave of a couple more key-presses a day.

~~~ .bash
$ ssh user@host 'uptime' # check uptime
$ ssh user@host 'df -h'  # check disk usage
~~~

#### Basic File Transfers

There are many ways of copying over file contents to and from a remote server, the most basic of which is piping 'cat' commands.
Below details an example of copying the contents of a local file to a remote location, taking advantage of the SSH capabilities highlighted in the first example.
The second example completes the inverse operation, bringing down a file from a remote location.

~~~ .bash
$ cat /file/on/local | ssh user@host 'cat > /file/on/remote' # local to remote
$ ssh user@host 'cat /file/on/remote' > /file/on/local       # remote to local
~~~

If you do not desire to copy across the full contents of a file and only require a single line to be appended to a remote file, the below example will complete this action.
The use of single quotes is by no coincidence, they are required for the supplied exclamation mark, preventing Bash string expansion.

~~~ .bash
$ echo 'Hello, world!' | ssh user@host 'cat >> /file/on/remote' # append line to remote
~~~

#### File Comparison

Even with the addition of a VCS like Git in my workflow, comparing deployed files with local copies is still a great tool to have.
Below is a command which compares the contents of a remote file with a supplied local file.

~~~ .bash
$ ssh user@host 'cat /file/on/remote' | diff /file/on/local -
~~~

### SCP

Sometimes you need a little more control over how and what files you wish to transfer, this is when SCP (Secure copy) can help.
The tool can be abstractly considered 'cp' with added SSH protocol support.

#### Transferring Files

Similar to the examples shown for basic SSH file transfers, you can use the following commands to copy between local and remote locations.

~~~ .bash
$ scp /file/on/local user@host:/file/on/remote # local to remote
$ scp user@host:/file/on/remote /file/on/local # remote to local
~~~

You also have the capability to transfer files between two different remote location, I have found this useful in multi-server distributed setups.

~~~ .bash
$ scp user@host1:/file/on/remote user@host2:/file/on/remote # copy between remotes
~~~

#### Transferring Directories

Transferring directories between local and remote locations is similar to the single file actions, differing with the addition of the '-r' recursive flag.

~~~ .bash
$ scp -r /dir/on/local user@host:/dir/on/remote # local to remote
$ scp -r user@host:/dir/on/remote /dir/on/local # remote to local
~~~

#### Limit Bandwidth Usage

SCP also adds support to limit the bandwidth available to the transfer.
In a similar fashion to recursively transferring directory files, all is a required is a '-l' flag and limit value supplied in kbit/s.

~~~ .bash
$ scp -l 56 user@host:/file/on/remote /file/on/local # remote to local @ 56kbps
~~~

#### Change Transfer Encryption

By default SCP uses AES-128 to encrypt the data transferred, which though secure, can result in slow transfers in large instances.
Weighing up each transfer on a separate security bases, you are able to speed up a transfer by using an alternative, weaker encryption like Blowfish or RC4.

~~~ .bash
$ scp -c blowfish user@host:/file/on/remote /file/on/local
~~~