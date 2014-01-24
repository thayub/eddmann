---
title: Move Last Git Commit to New or Existing Branch
slug: move-last-git-commit-to-new-or-existing-branch
abstract: Simple method of moving Git commits to new or existing branches.
date: 24th Jan 2014
---

Sometimes you may begin work on a specific branch (say 'master') and realise that it would be better off to move these commits into a separate branch.
This can be simply achieved using Git, either to a brand new branch or an existing branch.

### New Branch

The simpler of the two methods is to create a new branch who's HEAD is that of the existing branches last commit.
Using the following commands will create a new branch called 'feature' and then hard resets (staging and working tree) the existing branches HEAD to the previous commit.

~~~ .bash
$ git branch feature
$ git reset --hard HEAD~1 # or commit SHA1
~~~

### Existing Branch

If on the other hand you wish to move these commits into an existing branch you must first merge the new commits in and then follow the steps above.

~~~ .bash
$ git checkout feature
$ git merge master
$ git checkout master
$ git reset --hard HEAD~1 # or commit SHA1
~~~