---
title: Merge sort comparison in Java and Scala
slug: merge-sort-comparison-in-java-and-scala
abstract: A rudimentary comparison of Java and Scala using Merge sort.
date: 27th Nov 2013
---

Having only just recently got a complete shot of functional goodness in the form of the [Functional Programming Principles in Scala](http://www.coursera.org/course/progfun) [MOOC](http://en.wikipedia.org/wiki/Massive_open_online_course), my imperative standing is in a state of confusion.
Is mutability the devil, should every function not have side-effects, what really is a monad?
Okay, so I maybe joking alittle with these semi-rhetorical remarks - boy, have I read enough monad posts for one life-time.
Added to the main-stream application design consensus, the functional paradigm is making in-roads out of multi-core necessity (goodbye [Moore's law](http://en.wikipedia.org/wiki/Moore's_law)).

Scala is a statically typed, JVM language released in 2003 by Martin Odersky.
It had the ambitious goal of trying to meld the two some-what conflicting worlds of Object-oriented (business) and functional (academia) paradigms.
As the past decade of steady adoption has proved however (i.e. at Twitter), this has been a success.
Its well designed [homepage](http://www.scala-lang.org/) (a rarity in language cycles) gives you a small hit of what makes Scala so different from the ever growing JVM platform language world.
A truely unified type system (ala Smalltalk and Ruby), type-inference, lazy evaluation and for-comprehensions (commonly known as list-comprehensions) are only a small subset of the features that make up the language's power.

Okay, now with the small Scala love-fest over, I would like to show you a dramatic (though somewhat contrived) example of the differences between Java and Scala - and to some extent, functional and impreative idioms.
To demonstrate this I will be implemententing the divide-and-conquer [Merge sort](http://en.wikipedia.org/wiki/Merge_sort) algorithm, which works well to highlight some of the functional expressiveness present in Scala.
Obviously in a real-world setting this task would be handled by an heavily optimised implemenation such as Java's [Collections](http://docs.oracle.com/javase/7/docs/api/java/util/Collections.html) library.

### Java

    public class MergeSort {
        private static void merge(Comparable[] arr, Comparable[] tmp, int l, int m, int h) {
            for (int i = l; i <= h; i++) tmp[i] = arr[i]; // copy order into temp array
            int i = l, j = m + 1;
            for (int k = l; k <= h; k++) {
                if (i > m)                             arr[k] = tmp[j++]; // left complete
                else if (j > h)                        arr[k] = tmp[i++]; // right complete
                else if (tmp[j].compareTo(tmp[i]) < 0) arr[k] = tmp[j++]; // right < left
                else                                   arr[k] = tmp[i++]; // left < right
            }
        }

        private static void sort(Comparable[] arr, Comparable[] tmp, int l, int h) {
            if (l >= h) return; // 0..1
            int m = l + (h - l) / 2;
            sort(arr, tmp, l, m);     // left
            sort(arr, tmp, m + 1, h); // right
            merge(arr, tmp, l, m, h);
        }

        public static void sort(Comparable[] arr) {
            Comparable[] tmp = new Comparable[arr.length];
            sort(arr, tmp, 0, arr.length - 1);
        }

        public static void main(String[] args) {
            Integer[] arr = new Integer[] { 4, 2, 1, 3 };
            sort(arr);
            System.out.println(java.util.Arrays.toString(arr));
        }
    }

As you can see from the above example, the sorting routine consists of three separate methods, resulting in an in-place sort.
A temporary array is created once (same in size) and used to aid in the merging routine of the algorithm.
There are many optimisations I could include to help increase performance, for example, using [Insertion sort](http://en.wikipedia.org/wiki/Insertion_sort) for smaller sub-arrays and testing for pre-existing ordering.
Along with this, I have been alittle [relaxed](http://stackoverflow.com/questions/4830400/java-unchecked-call-to-comparetot-as-a-member-of-the-raw-type-java-lang-compa) in my use of the Comparable interface by not specifying a generic type.
However, this example successfully shows the key ingredients for implementing such an algorithm in an imperative fashion.

### Scala

<pre><code class="scala">def mergeSort[T](pred: (T, T) => Boolean)(xs: List[T]): List[T] = {
    def merge(ls: List[T], rs: List[T]): List[T] = (ls, rs) match {
        case (List(), _) => rs
        case (_, List()) => ls
        case (l :: ls1, r :: rs1) =>
            if (pred(l, r)) l :: merge(ls1, rs)
            else r :: merge(ls, rs1)
    }

    val m = xs.length / 2
    if (m == 0) xs
    else {
        val (l, r) = xs splitAt m
        merge(mergeSort(pred)(l), mergeSort(pred)(r))
    }
}

val intSort = mergeSort((_: Int) < (_: Int)) _
println(intSort(List(4, 2, 1, 3)))
</code></pre>

Well this looks alittle different, only two declared functions by-name, lets go through it piece-by-piece.
The first point of note is the use of [Currying](http://en.wikipedia.org/wiki/Currying) to initially provide a comparable predicate, followed then by the list of values.
This can be seen with the 'intSort' partially-applied function that is created using the passed in anonymous-function predicate.
The nested merge function takes advantage of pattern-matching and recursion to succinctly express the same problem as its Java counterpart.
Finally declared, the 'mergeSort' function is recursively called dividing and merging the resulting lists.
The returning result definition can be omitted, as Scala can take the last expression (in are case the if-expression) to be this value.

A couple of design considerations that should be taken into account are that it uses linked-lists (instead of arrays), allowing us to perform a more elegant looking pattern match.
Also, for simplicity I have not addressed tail-recursion and a O(N) cons stack blow-up is likely to occur with sufficient N.
Maybe in a future post I will address this and explore an example using a lazy-evaluated Stream to aid in performance.

### Resources

- [Scala School from Twitter](http://twitter.github.io/scala_school/)
- [Functional Programming Principles in Scala](http://www.coursera.org/course/progfun)
- [Principles of Reactive Programming](http://www.coursera.org/course/reactive)
- [Scalawags Podcast](http://www.scalawags.tv/)
- [Online Scala Interpreter](http://www.simplyscala.com/)
- [Algorithms, 4th Edition](http://algs4.cs.princeton.edu/home/)