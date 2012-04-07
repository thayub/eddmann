---
layout: post
title: IE6, IE7 & IE8, meet VMWare Fusion
---

Unfortunately as a web developer you will undoubtedly encounter the need to support one (or more) of the three browsers mentioned in this article title. 
It’s a sad but true state we are in and making it as seamless as possible goes someway in taking the sting out of it.

I’ve spent many hours researching into making these browser tests less painful and the first tool I got hooked on was a Windows only application called [IETester](http://www.my-debugbar.com/wiki/IETester/HomePage). 
This application packaged the different engines found in each version of IE, and allowed you to create new tabs based on the engine version you wished to test for. 
Initially, I thought this was a great idea but I encountered numerous issues with JavaScript execution and small differences in how the application rendered a page, leading me to have to look for another solution.

Fortunately, Microsoft have been nice enough to provide the web development community with multiple Virtual PC images that allow us to legally test for compatibility in a evaluative XP, Vista or 7 environment. 
Unfortunately, being Virtual PC images these work great for use on a Windows box but what about the people who haven’t got that 'luxury' *coughcough*. 
Well luckily for us there are a few workarounds that are available. 
One of which is using [ievms](https://github.com/xdissent/ievms), which does exactly what it says on its GitHub repo page and automates the installation for both Linux and Mac OS platforms using VirtualBox. I would highly recommend you check this out if you wish to use VirtualBox, personally I prefer VMWare Fusion.

### Setup

For this tutorial I will be using the Windows XP VHD, which includes IE6 as standard. To begin you will need to download this image from [Internet Explorer Application Compatibility VPC Image](http://www.microsoft.com/download/en/details.aspx?displaylang=en&id=11575). 
Once successfully downloaded you will be able to extract the .EXE using a tool such as [The Unarchiver](http://wakaba.c3.cx/s/apps/unarchiver.html).

You will now have two files of which to work with:
- Windows XP.vhd
- Windows XP.vmc

We only care about the VHD, as the next step is to convert that image into a Virtual Machine Disk (VMDK) to support VMWare Fusion. 
The easiest way I have researched into performing this task is to install [Q](http://www.kju-app.org/) which internally includes a component called <span class="snippet">qemu-img</span> which works well.

Once you have installed Q, open up your Terminal and navigate to the folder which holds the VHD, once in there run the command below:

{% highlight bash %}
$ /Applications/Q.app/Contents/MacOS/qemu-img convert -O\
  vmdk -f vpc Windows XP.vhd Windows XP.vmdk 
{% endhighlight %}

### Installation

Once your VHD has been successfully converted to a VMDK it is now time to open up VMWare Fusion and create a new virtual machine that uses the existing VMDK which we have created. 
Upon first boot you will be greeted with many Filed Needed/Found New Hardware dialogs which you can safely cancel and close at this time. 
Once you are at the desktop you can now install the VMware tools, which will provide the machine with the correct network adapter drivers for it to successfully reactivate itself.

<p class="title centre"><span>Installing IE7 on the newly created Virtual Machine</span></p>
<img src="/posts/ie6-ie7-ie8-meet-vmware-fusion/ie7.png" class="centre" />

You can now happily leave it here, and just create multiple copies of the VM to install the different versions of IE you wish to compatibility test to. 
I personally like to use a method I picked up from this blog post ‘[How I use VMWare Fusion and Snapshots](http://snook.ca/archives/other/vmware-fusion-snapshots)’. 
Using this method you go through the process of making an initial snapshot of your Windows XP installation with IE6 installed, then install IE7 and snapshotting the VM state again, and lastly snapshotting the VM with IE8 on. 
This method saves on disk space, and allows you to quickly switch between stable builds of each of the browsers you want to test on.

<p class="title centre"><span>A tree view of all the snapshots that I created for testing use</span></p>
<img src="/posts/ie6-ie7-ie8-meet-vmware-fusion/snapshots.png" class="centre" />

As you can see from the screenshot above I am currently using the IE6 snapshot with the ability to quickly switch to another browsers configuration in a couple of clicks. 
You will also notice that I have created a Firefox 3.6 snapshot, showing that this method does not only benefit IE testing but browser testing in general.