---
title: Implementing a Queue in Java using Arrays and Linked Lists
slug: implementing-a-queue-in-java-using-arrays-and-linked-lists
abstract: Two alternative methods of implementing the common place Queue data-structure.
date: 24th Dec 2013
---

Following on from my previous post on implementing a [stack](/posts/implementing-a-stack-in-java-using-arrays-and-linked-lists/) in Java, I now wish to discuss the as important queue data-structure.
Similar in kind to the restrictions placed upon the stack implementation, the queue only allows mutation via two methods.
Addition (enqueue) occurs at the end of the collection, and removal (dequeue) from the beginning, resulting in a FIFO (First-In-First-Out) structure.
Queues are typically used in the application of buffers to store data, objects, events etc. that are to be held for future sequential processing.
Discussed in the post on stacks, you are more than likely never going to have to implement such a data-structure in practical use-cases, as the language libraries will already include such an implementation (i.e. [C++ STL](http://www.cplusplus.com/reference/queue/queue/) and [PHP SplQueue](http://www.php.net/manual/en/class.splqueue.php)).

The following examples solve the same problem, and as such I have again created a simple interface that each implementation must fulfill.
Using this approach removes any worry from the user about specific implementation details and permits switching to alternative conforming instances later on, if the use-case warrants it.

~~~ .java
interface Queue<T> {
    Queue<T> enqueue(T ele);
    T dequeue();
}
~~~

### Array-based implementation

The first implementation stores the underlying collection in a fixed-sized array.
Discussed in the previous post on stacks, this approach provides constant time 'O(1)' lookup on all items stored in the array, however, this is not of concern to us in this case.
Adding an element to the queue first requires a check to see if the underlying array is full, if so it is doubled in size.
This action occurs at this time instead of at the end of addition to the last available slot, as it would be a wasted exercise to preemptively perform the costly resize if no other items were to be queued.
Once this action has taken place the item is added to the next available slot, which is then subsequently checked to see if the new 'next' index overflows the underlying array.
In such a case a crafty 'wrap-around' optimisation takes place were the structure can store the next item at the beginning of the array.
Performing such an optimisation allows the structure to reuse the current underlying array until it is absolutely necessarily to warrant a larger capacity.
As a result, the resize method must be aware of this 'wrap-around' offset when copying over the current contents of the collection to the new array, the modular arithmetic is put in place for this case.
Finally, to remove (dequeue) the first element from the collection, the 'first' index is used to access the desired item.
This items slot is then nulled to stop [loitering](http://stackoverflow.com/questions/18109915/java-loitering-and-garbage-collection) and the 'wrap-around' technique for the 'next' index is put into affect.
Array maintenance can then be carried out, cutting the array in half if the queue now only contains a quarter of its previous size.

~~~ .java
public class QueueArray<T> implements Queue<T> {

    private T[] arr;

    private int total, first, next;

    public QueueArray()
    {
        arr = (T[]) new Object[2];
    }

    private void resize(int capacity)
    {
        T[] tmp = (T[]) new Object[capacity];

        for (int i = 0; i < total; i++)
            tmp[i] = arr[(first + i) % arr.length];

        arr = tmp;
        first = 0;
        next = total;
    }

    public QueueArray<T> enqueue(T ele)
    {
        if (arr.length == total) resize(arr.length * 2);
        arr[next++] = ele;
        if (next == arr.length) next = 0;
        total++;
        return this;
    }

    public T dequeue()
    {
        if (total == 0) throw new java.util.NoSuchElementException();
        T ele = arr[first];
        arr[first] = null;
        if (++first == arr.length) first = 0;
        if (--total > 0 && total == arr.length / 4) resize(arr.length / 2);
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

In a similar vein to the stack examples, the second implementation uses a linked-list to store the queues contents.
Using such an approach provides a very efficient, succinct implementation with low computation complexity.
Usual performance considerations of a singly-link list can be dismissed as keeping a reference to both ends of the list provides constant time 'O(1)' insertion and deletion from the collection.
Adding an item to the queue first stores a temporary reference to the current 'last' element of the list.
The structure is then able to go about storing the the new node instance referenced by the 'last' variable.
If previously the collection was empty we set the 'first' element to this new item as well as the consistent last reference.
However, in the case that there are already items present, we simply set the previous last elements 'next' reference to this new node.
Removing (dequeuing) elements from the collection is also a trivial task, simply returning the node referenced from the 'first' variable.
This reference is then updated to the returned nodes 'next' reference, and a simple check to clear the last reference if the collection is now empty takes place.

~~~ .java
public class QueueLinkedList<T> implements Queue<T> {

    private int total;

    private Node first, last;

    private class Node {
        private T ele;
        private Node next;
    }

    public QueueLinkedList() { }

    public QueueLinkedList<T> enqueue(T ele)
    {
        Node current = last;
        last = new Node();
        last.ele = ele;

        if (total++ == 0) first = last;
        else current.next = last;

        return this;
    }

    public T dequeue()
    {
        if (total == 0) throw new java.util.NoSuchElementException();
        T ele = first.ele;
        first = first.next;
        if (--total == 0) last = null;
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

Below is an example showing the linked-list implementation in action.
Similar to the stack examples, declaring the instance variable as the queue interface allows for a simple switch to another implementation if future requirements warrant it.

~~~ .java
Queue<String> greeting  = new QueueLinkedList<>();

greeting.enqueue("Hello").enqueue(", ").enqueue("World!");

System.out.println(greeting.dequeue() + greeting.dequeue() + greeting.dequeue());
~~~

### Resources

- [Bags, Queues, and Stacks](http://algs4.cs.princeton.edu/13stacks/)
- [Java Loitering and Garbage Collection](http://stackoverflow.com/questions/18109915/java-loitering-and-garbage-collection)
- [PHP SplQueue](http://www.php.net/manual/en/class.splqueue.php)
- [C++ STL Queue](http://www.cplusplus.com/reference/queue/queue/)