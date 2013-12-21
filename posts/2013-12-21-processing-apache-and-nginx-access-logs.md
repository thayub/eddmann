---
title: Processing Apache and Nginx Access Logs
slug: processing-apache-and-nginx-access-logs
abstract: Gathering useful information from the data stored in Apache and Nginx access logs.
date: 21st Dec 2013
---

Tools such as [AWStats](http://awstats.sourceforge.net/) and [Logstalgia](http://code.google.com/p/logstalgia/) are great, but sometimes they can be over-kill for the problem we are trying to solve.
It turns out that with a couple of simple Unix commands we can gather a lot of useful information from the data stored in our the access logs.
Both Apache and Nginx by default use the [combined](http://httpd.apache.org/docs/1.3/logs.html#combined) log format which I will be basing this posts examples on.
Below are two different methods of accessing either an uncompressed single file or multiple compressed files (following the supplied wild-card pattern).

~~~ .bash
$ cat access.log # uncompressed file
$ zcat access.log-*.gz # compressed files i.e. access.log-20131221.gz
~~~

### Log Data to Information

<figcaption>404 Request Responses</figcaption>

~~~ .bash
$ cat access.log | awk '($9 ~ /404/)' | awk '{ print $7 }' | sort | uniq -c | sort -rn | head -n 25
~~~

Using 'awk' we are able to filter the access log entires down to only the ones that include the 404 status code.
In this case we are then displaying the most requested pages of this type, in descending order.

<figcaption>Request User Agents</figcaption>

~~~ .bash
$ cat access.log | awk -F\" '{ print $6 }' | sort | uniq -c | sort -frn | head -n 25
~~~

The command above displays the top 25 user agents (browser, operating system) that have requested a resource from the web server.
The 'awk' command in this instance uses a defined field separator of " to successfully parse the user agent string.

<figcaption>Request IP Addresses</figcaption>

~~~ .bash
$ cat access.log | awk '{ print $1 }' | sort | uniq -c | sort -rn | head -n 25
$ cat access.log | awk '{ print $1 }' | sort | uniq -c | sort -rn | head -n 25 | \
    awk '{ printf("%5d\t%-15s\t", $1, $2); system("geoiplookup " $2 " | cut -d \\: -f2 ") }'
~~~

Above are two examples of displaying the top 25 IP addresses based on total requests.
The second command uses the GeoIP package to include the country the IP address originates from.
This package can be installed on a CentOS setup using the [EPEL](https://fedoraproject.org/wiki/EPEL) repository.

<figcaption>Count Unique Visits</figcaption>

~~~ .bash
$ cat access.log | awk '{ print $1 }' | sort | uniq -c | wc -l
# or today
$ cat access.log | grep `date '+%e/%b/%G'` | awk '{ print $1 }' | sort | uniq -c | wc -l
# or this month
$ cat access.log | grep `date '+%b/%G'` | awk '{ print $1 }' | sort | uniq -c | wc -l
~~~

The commands above will provide you with a total count of unique visits based on IP address.
You are also able to run the log file through the 'grep' command before processing, to only include entries that have occurred today or this month.

<figcaption>Ranked by Response Codes</figcaption>

~~~ .bash
$ cat access.log | awk '{ print $9 }' | sort | uniq -c | sort -rn
~~~

This simple command is very useful to quickly observe the total counts based on returned response code.

<figcaption>Most Popular URLS</figcaption>

~~~ .bash
$ cat access.log | awk '{ print $7 }' | sort | uniq -c | sort -rn | head -n 25
~~~

A trivial replacement for some Google Analytics statistics, reporting how many hits the top 25 resources have tallied.

<figcaption>Real-time IP-Page Requests</figcaption>

~~~ .no-highlight
$ tailf access.log | awk '{ printf("%-15s\t%s\t%s\t%s\n", $1, $6, $9, $7) }'
$ tailf access.log | awk '{
    "geoiplookup " $1 " | cut -d \\: -f2 " | getline geo
    printf("%-15s\t%s\t%s\t%-20s\t%s\n", $1, $6, $9, geo, $7);
  }'
~~~

The final two commands are most likely my favorite as they provide me with real-time access information.
These commands report on each IP address, request and response that have recently occurred on the server.
Using [tailf](http://linuxcommand.org/man_pages/tailf1.html) instead of a typical 'tail -f' has the benefit of not accessing the file when it is not growing.

### Resources

- [System: Analyzing Apache Log Files](http://www.the-art-of-web.com/system/logs/)
- [Parsing access.log and error.logs using linux commands](https://rtcamp.com/tutorials/nginx/log-parsing/)
- [How to gather IP and User Agent info from web log with AWK?](http://stackoverflow.com/questions/16128472/how-to-gather-ip-and-user-agent-info-from-web-log-with-awk)