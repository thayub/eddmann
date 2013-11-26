---
title: Making Gitflow the way you want it to
slug: making-gitflow-the-way-you-want-it-to
abstract: Using Git in a way that just makes complete sense.
date: 26th Aug 2012
---

I have been a part-time Git user for alittle over a year now, before this I had dabbled with using [Subversion](http://subversion.apache.org/) but never for anything too serious.
I say part-time as throughout the year I never fully got to grips with all the ideologies/tools provided to aid in my development lifecyle process - in particular branching.

Sure, I had attempted to get my head around the great documentation found on the offical [Git website](http://git-scm.com/), along with a boat load of screencasts, but putting this into practise just never happened.
Up until recently I had been very happy working in 'master' and maybe if I'm feeling alittle crazy adding a temporary branch for a feature I may be experimenting on.

### Gitflow to the rescue

This has all changed since I found Gitflow (alittle late to the party I know), In 2010 Vincent Driessen posted on his site ([A successful Git branching model](http://nvie.com/posts/a-successful-git-branching-model/)) about how he used Git for both personal and work development.
He discussed incorporating the use of the branch types listed below:

* Master
* Hotfix
* Release
* Develop
* Feature

On-top of this he released a set of [Git extensions](http://github.com/nvie/gitflow), to help speed up and ease the learning curb.
If you are a Homebrew user like myself, installing Gitflow is as easy as running:

    $ brew install git-flow

And then to begin using Gitflow on an existing Git repository, followed by creating a new feature called 'test':

    $ git flow init
    $ git flow feature start test

Now there is no point in me repeating the concepts put forth in Vincent's well laid out blog post, or adding yet another simple tutorial to the mix.
Instead however, I will leave you with a list of resouces that I have found useful when learning this subject:

### Resources

* [A successful Git branching model - Vincent Driessen](http://nvie.com/posts/a-successful-git-branching-model/)
* [Github: Gitflow Repository](https://github.com/nvie/gitflow)
* [A short introduction to Gitflow - Video](http://vimeo.com/16018419)
* [On the path with Gitflow - Video](http://vimeo.com/37408017)
* [Introduction to Git with Scott Chacon - Video](http://www.youtube.com/watch?v=ZDR433b0HJY)