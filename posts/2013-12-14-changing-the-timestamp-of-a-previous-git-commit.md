---
title: Changing the timestamp of a previous Git commit
slug: changing-the-timestamp-of-a-previous-git-commit
abstract: Simple ways to alter a past or present commit date (timestamp).
date: 14th Dec 2013
---

Git has two different types of timestamp associated with a commit.
Although both may typically hold the same value they are used in subtly different ways.
The author (GIT_AUTHOR_DATE) is the user who originally created the work (i.e. a patch), where as the committer (GIT_COMMITTER_DATE) is the user who last applied the work (i.e. applied patch or rebase).
The author date is the one displayed when the log is accessed, however, the commit date is used when given the filter (--since, --until) options, which seems a little odd.
To avoid confusion you can include the committer date within your log display by setting the --format option.

~~~ .bash
$ git log --format=fuller
~~~

### Ch-ch-Changes...

A tendency I have picked up is to be very commit happy when developing locally, and then find myself rebasing a lot before pushing to a remote.
As a result of this I sometimes am required to alter timestamps of certain commits to make them more meaningful.
I found that the simplest of which is adding a specified timestamp to the current commit.
The author date is specified with the --date option where as the committer timestamp has to be changed with an environment variable.

~~~ .bash
$ git commit --date="Sat, 14 Dec 2013 12:40:00 +0000" # only author
$ GIT_COMMITTER_DATE="`date -R`" git commit --date "`date -R`" # for both
~~~

If on the other hand we wish to amend the last author commit you can execute the following, very similar command.
I should warn you to be wary of running these next commands on a branch with commits that you have already pushed to a remote - due to Git's hashing nature this will alter the SHA's of all commits that come after the desired commit as well.

~~~ .bash
$ git commit --amend --date "`date -R`" # include '-C HEAD' to bypass commit message editing
~~~

Finally, to alter a previous commit by SHA reference hash, you can run the following command (altered from [GitFaq](http://git.wiki.kernel.org/index.php/GitFaq#How_can_I_tweak_the_date_of_a_commit_in_the_repo.3F)).

~~~ .bash
$ git filter-branch --env-filter \
"if test \$GIT_COMMIT = 'e6dbcffca68e4b51887ef660e2389052193ba4f4'
then
    export GIT_AUTHOR_DATE='Sat, 14 Dec 2013 12:40:00 +0000'
    export GIT_COMMITTER_DATE='Sat, 14 Dec 2013 12:40:00 +0000'
fi" && rm -fr "$(git rev-parse --git-dir)/refs/original/"
~~~

### Viewing the alterations

We can now check out our handy work by looking at the log again, providing --since and --until options to filter the results.

~~~ .bash
$ git log --since="yesterday"
$ git log --since="1 month ago" --until="yesterday"
~~~

### Resources

- [How can I tweak the date of a commit in the repo?](http://git.wiki.kernel.org/index.php/GitFaq#How_can_I_tweak_the_date_of_a_commit_in_the_repo.3F)
- [How can one change the timestamp of an old commit in Git?](http://stackoverflow.com/questions/454734/how-can-one-change-the-timestamp-of-an-old-commit-in-git)