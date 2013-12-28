---
title: Least Significant Digit (LSD) Radix Sort in Java
slug: least-significant-digit-lsd-radix-sort-in-java
abstract: Radix sort using an iterative bucket and queue implementation.
date: 28th Dec 2013
---

Radix sort is a O(digits·keys) sorting algorithm which relies on integer key grouping to successfully process and naturally order the specified data set.
Basing the sort on structure and positional notation, many other data types which can be represented in integer form (i.e. ASCII characters) can take advantage of the algorithm.
Sorting occurs by acting on the comparison between item digits in the same position.
Two alternative version of the algorithm exists, both tackling the problem from the opposite direction.
In this post I will be describing an iterative lowest significant digit implementation which as the name hints at, starts processing from the right most digit position.
This implementation results in a [stable](http://en.wikipedia.org/wiki/Stable_sort#Stability) sort, where as the other implementation, tackles the most significant digit first and can not make such guarantees.
In a stable sorting algorithm the initial ordering of equal keys is left unchanged in the result.

~~~ .java
public static void radixSort(int[] arr)
{
    Queue<Integer>[] buckets = new Queue[10];
    for (int i = 0; i < 10; i++)
        buckets[i] = new LinkedList<Integer>();

    boolean sorted = false;
    int expo = 1;

    while ( ! sorted) {
        sorted = true;

        for (int item : arr) {
            int bucket = (item / expo) % 10;
            if (bucket > 0) sorted = false;
            buckets[bucket].add(item);
        }

        expo *= 10;
        int index = 0;

        for (Queue<Integer> bucket : buckets)
            while ( ! bucket.isEmpty())
                arr[index++] = bucket.remove();
    }

    assert isSorted(arr);
}
~~~

Above shows an example of a queue-based least significant digit radix sorting implementation.
Starting from the right-most digit, the process occurs over multiple passes, distributing each item into calculated buckets, based on positional key.
After each pass through the collection the items are retrieved in order from each bucket.
This process is repeated up and including to the length of the longest key.

~~~ .java
private static boolean isSorted(int[] arr)
{
    for (int i = 1; i < arr.length; i++)
        if (arr[i - 1] > arr[i])
            return false;

    return true;
}
~~~

To make sure that the resulting processed data set is correctly sorted an assertion was included.
Using such a feature is great in development, allowing you to verify the correctness of a specific invariant.
This assertion can be activated at run-time by inclusion of the '-ea' option in the 'java' command.

### Resources

- [Radix Sort](http://www.dcs.gla.ac.uk/~pat/52233/slides/RadixSort1x1.pdf)
- [NIST: Radix Sort](http://xlinux.nist.gov/dads/HTML/radixsort.html)
- [What is natural ordering when we talk about sorting?](http://stackoverflow.com/questions/5167928/what-is-natural-ordering-when-we-talk-about-sorting)