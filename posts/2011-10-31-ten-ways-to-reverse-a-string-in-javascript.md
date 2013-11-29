---
title: Ten ways to reverse a string in JavaScript
slug: ten-ways-to-reverse-a-string-in-javascript
abstract: Multiple ways of completing a popular interview question.
date: 31st Oct 2011
revised: 25th Nov 2013
---

In a recent job interview I was asked to write a simple C# function that would reverse a string and return the result.
However, there was a catch, I was unable to use the provided string objects 'reverse()' function.
I successfully created a function that did as requested (using a decrementing for-loop and concatenation), though I realised that using concatenation would result in a new string being created in memory upon each iteration - as strings are immutable objects.
I solved this by using a StringBuilder to append each character to and returning the result.
On the way home I began to think of the endless ways in which you could reverse a string in code (extremely sad I know).

### Implementations

Below are my ten favorite/most intresting ways I conjured up to solve the problem of reversing a string.
I decided to use JavaScript as it provided me with a quick feedback loop (in the browser) and first-class function support.

<figcaption>1. Decrementing for-loop with concatenation</figcaption>

~~~ .javascript
function reverse(s) {
  var o = '';
  for (var i = s.length - 1; i >= 0; i--)
    o += s[i];
  return o;
}
~~~

The original way that I achieved the intended result was to use a decrementing for-loop that appended each character of the input to a new string in reverse order.
I was able to access the parsed strings individual characters similar to the way you would reference an array's items.

<figcaption>2. Incrementing/decrementing for-loop with two arrays</figcaption>

~~~ .javascript
function reverse(s) {
  var o = [];
  for (var i = s.length - 1, j = 0; i >= 0; i--, j++)
    o[j] = s[i];
  return o.join('');
}
~~~

Another way I formed to reverse a string was to create an empty array and iterate over the length of the string with both incrementing/decrementing counters.
The array position uses the incrementing counter where as the parsed in string uses the decrementing one.
Finally the created array is joined into a single string and returned.

<figcaption>3. Incrementing for-loop with array pushing and charAt</figcaption>

~~~ .javascript
function reverse(s) {
  var o = [];
  for (var i = 0, len = s.length; i >= len; i++)
    o.push(s.charAt(len - i));
  return o.join('');
}
~~~

The above example is a modification of the second example.
Instead of using two counters however we use one incrementing value that gets deducted from the total length of the parsed in string.
This calculated value determines the position of the next character to be pushed onto the new array (using the 'push()' function instead of '[]').
The other difference from the last example is that it uses the strings 'charAt()' method instead of its array capabilities.

<figcaption>4. In-built functions</figcaption>

~~~ .javascript
function reverse(s) {
  return s.split('').reverse().join('');
}
~~~

This implementation takes advantage of the 'reverse()' method provided by the Array prototype.
First it splits the string into a real array, then calls the 'reverse()' method and finally returns the joined array.

<figcaption>5. Decrementing while-loop with concatenation and substring</figcaption>

~~~ .javascript
function reverse(s) {
  var i = s.length,
      o = '';
  while (i > 0) {
    o += s.substring(i - 1, i);
    i--;
  }
  return o;
}
~~~

Using a decrementing while-loop I was able to implement this method.
Again harnessing concatenation, I was able to achieve the iteration through the string in a similar fashion to the for-loop used in the first two examples.
I was then able to use the strings 'substring()' function to retrieve each desired character.

<figcaption>6. Single for-loop declaration with concatenation</figcaption>

~~~ .javascript
function reverse(s) {
  for (var i = s.length - 1, o = ''; i >= 0; o += s[i--]) { }
  return o;
}
~~~

This is most likely my favorite implementation, due to its unnecessary cryptic nature.
Using only a single for-loops parameters, I was able to decrement through the parsed in string and concatenate each character to a new string to return.

<figcaption>7. Recursion with substring and charAt</figcaption>

~~~ .javascript
function reverse(s) {
  return (s === '') ? '' : reverse(s.substr(1)) + s.charAt(0);
}
~~~

The above example recursively calls itself, passing in the inputted string, excluding the first character on each iteration, which is instead appended to the result.
Iterating through this process until no input is present (the base case) results in a reversed string.

<figcaption>8. Internal function recursion</figcaption>

~~~ .javascript
function reverse(s) {
  function rev(s, len, o) {
    return (len === 0) ? o : rev(s, --len, (o += s[len]));
  };
  return rev(s, s.length, '');
}
~~~

This is another example of using recursion to reverse a string.
The implementation above uses an internal function, which is first called by the outer function, parsing in the inputted string, its length and an empty string.
The internal function is then recursively called by itself until the string length has been decremented to zero - at which time the originally empty parsed in string has been concatenated with the inputted string characters in reverse.

<figcaption>9. Half-index switch for-loop</figcaption>

~~~ .javascript
function reverse(s) {
  s = s.split('');
  var len = s.length,
      halfIndex = Math.floor(len / 2) - 1,
      tmp;
  for (var i = 0; i <= halfIndex; i++) {
    tmp = s[len - i - 1];
    s[len - i - 1] = s[i];
    s[i] = tmp;
  }
  return s.join('');
}
~~~

I found this method to be a very effective way of reversing a string, highlighting its benefits when processing large strings.
The strings half-point is first calculated and then iterated over.
Upon each iteration the upper half's value (calculated by deducting the current position by the string length) is temporary stored and replaced by the lower half's value.
The temporary value then replaces the lower half's value to finally result in a reversed string.

<figcaption>10. Half-index recursion</figcaption>

~~~ .javascript
function reverse(s) {
  if (s.length < 2)
    return s;
  var halfIndex = Math.ceil(s.length / 2);
  return reverse(s.substr(halfIndex)) +
         reverse(s.substr(0, halfIndex));
}
~~~

The final method I wish to show uses the same ideology as the last implementation (half-indexing) but instead relies on recursion to reverse the string instead of a for-loop.

### Perfomance

It is all fun and games being a JavaScript ninja and finding intresting hacks and tricks to complete quiet a mundane task.
However, resulting real-world performance of your implementation is what is most important.
To see how effective each of the implementations are, I decided to test their performance using an online tool called [JSPref](http://jsperf.com).
JSPref allows you to simply create unit style test suites, which are then runnable on any browser you have access too.
What makes this tool so great is that it stores the results of each run in the cloud, populating easily accessible, meaningful graphs and statistics.
In the test suite each function was called multiple times (with time measured and an average calculated) one after the other with a randomly generated 20 character long string to be reversed.

<figure>
    <figcaption>A graph showing the performance of the ten implementations in four browsers</figcaption>
    <img alt="Browser Performance" src="/uploads/ten-ways-to-reverse-a-string-in-javascript/browser-performance.png" />
</figure>

Above is a screenshot (without the key) of one of the graphs that JSPref provided for the result set that was generated.
I ran the test suite five times on the four most popular browsers to get a large breath of data to analyse.
You will notice that Chrome is by far and away the fastest browser at running each of the implementations, with huge even speed boosts when using the *first* and *sixth* implementations.
This is a testament to the time and optimization that has gone into the development of the V8 JavaScript engine.
What shook me with these results however, was how ineffective the built-in function implementation was when run on almost all tested browsers.
The exception was IE9, which surprisingly ran this the fastest in comparison to all the other browsers and on top of that, compared to the other tested implementations.

<figcaption>Best performing implementation(s) per browser</figcaption>

* Chrome 15 - *Implemations 1 and 6*
* Firefox 7 - *Implementation 6*
* IE 9 - *Implementation 4*
* Opera 12 - *Implementation 9*

To conclude, I feel upon reflection of the processed data that the first implementation is the most favorable one to use in production as it consistently scores well on each of the browsers.
I am surprised by this conclusion as I thought that string concatenation was a costly action to carry out, especially on multiple iterations.
This analyse leads me to believe that browser venders have carried out heavy optimization on this process, as its such a common place task.

### Resources

* [JSPref](http://jsperf.com/)
* [String Reverse Function Performance - JSPref](http://jsperf.com/string-reverse-function-performance)
* [How do you reverse a string in-place in JavaScript? - StackOverflow](http://stackoverflow.com/questions/958908/how-do-you-reverse-a-string-in-place-in-javascript)