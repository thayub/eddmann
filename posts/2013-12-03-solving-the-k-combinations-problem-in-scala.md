---
title: Solving the k-combinations problem in Scala
slug: solving-the-k-combinations-problem-in-scala
abstract: Solving the N-choose-k problem different ways in Scala.
date: 3rd Dec 2013
---

More often than not there are many different ways to solve a particular task.
I found this trait present when coding up a solution to the k-combinations (N-choose-k) problem.
A [combination](http://en.wikipedia.org/wiki/Combination) is the action of selecting a set amount of elements from a larger group, where order is not considered (dissimilar to a [permutation](http://en.wikipedia.org/wiki/Permutation)).
An example of a combination is in the cards you are dealt in a poker-hand, out of the possible 52 cards you are drawn 5 (52 choose 5).
We are able to calculate the unique hand offerings in many different ways, allowing us to predict how likely it is for an individual card to be dealt.
In this post I will be showing examples solving the 10 choose 2 problem.

The first implementation used a brute-force approach, generating all the pair-combinations and then returning the total length.
This is the most naive solution, however, it is a good example of how useful Scala's ranges and for-expressions are.

~~~ .scala
val combinations = (for {
    i <- 1 to 10
    j <- 1 until i
} yield (i, j)) length
~~~

The second example I developed uses recursion, by identifying the simple observation that there are only two possible outcomes when you choose k elements from N items.
Either, you choose a particular element or your do not, with this simple assertion you are able to code a succinct [recursive algorithm](http://en.wikipedia.org/wiki/Binomial_coefficient#Recursive_formula).

~~~ .scala
def choose(n: Int, k: Int): Int =
    if (k == 0 || k == n) 1
    else choose(n - 1, k - 1) + choose(n - 1, k)

val combinations = choose(10, 2)
~~~

The final example uses an implicit class which provides Ints with the ability to call the method in the typical fashion we express it (52 choose 5).
I was able to use Scala's foldLeft to define the factorial calculation without the typical use of recursion, which I found very interesting.
Using this private method I was able to write the 'choose' method based on the well known [mathematical equation](http://en.wikipedia.org/wiki/Binomial_coefficient#Factorial_formula).
Finally, I also provided access to the factorial calculation by way of the '!' method.
There is no real requirement for this in the described example but highlights well Scala's flexiable method naming capabilities.

~~~ .scala
implicit class Combinations(n: Int) {
    private def fact(n: Int): Int = (1 to n).foldLeft(1)(_ * _)
    def ! = fact(n) // allows 10!
    def choose(k: Int): Int = fact(n) / (fact(n - k) * fact(k))
}

val combinations = 10 choose 2
~~~

To check that all these examples returned the same result I used the basic observation that a Set of all the results will not include duplicates, so the size must equal one.

~~~ .scala
if (Set(a, b, c).size == 1) "Woot!" else "Nope!"
~~~