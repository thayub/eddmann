---
title: Implementing a Stack in Java using Arrays and Linked Lists
slug: implementing-a-stack-in-java-using-arrays-and-linked-lists
abstract: Two alternative methods of implementing the common place Stack data-structure.
date: 22nd Dec 2013
---

The stack is a fundamental data-structure used extensively in algorithm design and program implementation.
At an abstract level it can be described very simply, as it only allows for addition (pushing) of new and removal (popping) of existing elements from the top of the stack.
This description can be abbreviated to LIFO, which stands for Last-In-First-Out.
Although you will most likely not have to implement such a structure for practical use-cases, it can be very useful to 'look under the hood' to gain a better understanding of what is going on.
Doing so will make you more aware of when this data-structure can be best used.

The following examples solve the same problem, and as such I have created a simple interface that each implementation must fulfill.
Contractual agreements like this are great when you do not want the implementation details to effect the API that is available, allowing the user to use them interchangeably.

~~~ .java
interface Stack<T> {
    Stack<T> push(T ele);
    T pop();
}
~~~

### Array-based implementation

The first implementation I would like to discuss stores the stack contents in an underlying fixed-sized array.
Using such a method provides constant time 'O(1)' lookup of all items in the collection, however in the stacks case this benefit is not warranted.
An initial array (size of 2) is first initialised, and when new elements are added the running total is incremented.
Additions to the array occur in constant amortized time 'O(1)', as they are inserted at the end.
If the array reaches its limit we then have to do the linear time 'O(n)' task of creating a new array of double the size, and then copying the contents over.
Using the 'System.arraycopy' method call is a more [performant](http://stackoverflow.com/questions/8526907/is-javas-system-arraycopy-efficient-for-small-arrays) alternative to building up the new array ourselves.
When an element is removed (popped) from the stack a check is done to see if the array is now a quarter full, if so the array is again resized, but this time cut in half.
As resizing the array is a very costly act we want to do it as infrequently as possible, using the double and quarter rules provides us with a good balance in typical use-cases.

One point you can pick up from this example is maybe the overlooked initial capacity parameter you can initialise an [ArrayList](http://docs.oracle.com/javase/7/docs/api/java/util/ArrayList.html) with.
Implemented conceptional the same, doing so can increase performance greatly if you have an estimate (heuristics) on how large the list is going to grow.

~~~ .java
public class StackArray<T> implements Stack<T> {

    private T[] arr;

    private int total;

    public StackArray()
    {
        arr = (T[]) new Object[2];
    }

    private void resize(int capacity)
    {
        T[] tmp = (T[]) new Object[capacity];
        System.arraycopy(arr, 0, tmp, 0, total);
        arr = tmp;
    }

    public StackArray<T> push(T ele)
    {
        if (arr.length == total) resize(arr.length * 2);
        arr[total++] = ele;
        return this;
    }

    public T pop()
    {
        if (total == 0) throw new java.util.NoSuchElementException();
        T ele = arr[--total];
        arr[total] = null;
        if (total > 0 && total == arr.length / 4) resize(arr.length / 2);
        return ele;
    }

    @Override
    public String toString()
    {
        return java.util.Arrays.toString(arr);
    }

}
~~~

### Linked-List implementation

The second example is more on par with what you might expect from a language implementation.
Using a Linked-List is tailor made to store the contents of a stack, handling the actions required with great performance results.
Unlike the array implementation, using a Linked-List provides us with constant time 'O(1)' guarantees when adding an element, as no underlying array requires resizing.
On top of this it also provides constant time 'O(1)' guarantees when removing (popping) an element, as only a reference requires modification.
This implementation differs in that it creates a new node instance per addition, each storing their supplied value and reference to the following node.
These links allow us to keep the stack intact and eventually traverse the entire collection, once emptied.
No upfront memory costs result when using a Linked-List as you only consume the space required per node, when a new value is pushed to the stack.
However, the overhead of each node being an object instance should be taken into consideration.
Another limitation of a Linked-List is the linear 'O(n)' traversal time, however, this is not an issue in this case as we are only concerned with the first (most recent) element.

~~~ .java
public class StackLinkedList<T> implements Stack<T> {

    private int total;

    private Node first;

    private class Node {
        private T ele;
        private Node next;
    }

    public StackLinkedList() { }

    public StackLinkedList<T> push(T ele)
    {
        Node current = first;
        first = new Node();
        first.ele = ele;
        first.next = current;
        total++;
        return this;
    }

    public T pop()
    {
        if (first == null) new java.util.NoSuchElementException();
        T ele = first.ele;
        first = first.next;
        total--;
        return ele;
    }

    @Override
    public String toString()
    {
        StringBuilder sb = new StringBuilder();
        Node tmp = first;
        while (tmp != null) {
            sb.append(tmp.ele).append(", ");
            tmp = tmp.next;
        }
        return sb.toString();
    }

}
~~~

### Example Usage

Below is an example showing the array implementation in action.
As you can see I have declared the variable instance as the Stack interface type, doing so allows me to easily switch out the implementation if future requirements warrant it.

~~~ .java
Stack<String> greeting = new StackArray<>();

greeting.push("!").push("World").push("Hello, ");

System.out.println(greeting.pop() + greeting.pop() + greeting.pop()); // Hello, World!
~~~