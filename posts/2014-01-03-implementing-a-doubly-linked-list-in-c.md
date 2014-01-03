---
title: Implementing a Doubly Linked-List in C
slug: implementing-a-doubly-linked-list-in-c
abstract: Simple C implementation of a Doubly Linked-List.
date: 3rd Jan 2014
---

Following on from the discussion on implementing a [singly linked-list](/posts/implementing-a-singly-linked-list-in-c/) in C, a logical follow-up data-structure is the doubly linked-list.
In a similar fashion, the structure is composed from a set of sequentially linked nodes, each now containing references (pointers) to not only the next node but the previous one to.
This structure is useful if the use-case dictates the desire to traverse the list both forwards and backwards, or quickly determine preceding and following elements from a given node.
The head and tail nodes can be terminated with either [sentinel nodes](http://en.wikipedia.org/wiki/Sentinel_node) (referred to as 'circularly linked' if only one is used) or like in the implementation shown below, NULL.
An observational implementation difference between the two structures is that through storing the previous and next reference, it can significantly simplify the complexity and running time of certain operations (removal from the tail being the most obvious).

~~~ .c
#include <stdio.h>
#include <stdlib.h>
#include <stdbool.h>

typedef struct node {
    int item;
    struct node *prev, *next;
} node;

node *head, *tail;

void insert(int item, bool at_tail)
{
    node *ptr = (node*) malloc(sizeof(node));
    ptr->item = item;
    ptr->prev = ptr->next = NULL;

    if (NULL == head) {
        head = tail = ptr;
    } else if (at_tail) {
        tail->next = ptr;
        ptr->prev = tail;
        tail = ptr;
    } else {
        ptr->next = head;
        head->prev = ptr;
        head = ptr;
    }
}

int delete(bool from_tail)
{
    if (NULL == head) {
        printf("Empty list.\n");
        exit(1);
    } else if (from_tail) {
        node *ptr = tail;
        int item = ptr->item;
        tail = ptr->prev;
        if (NULL == tail) head = tail;
        else tail->next = NULL;
        free(ptr);
        ptr = NULL;
        return item;
    } else {
        node *ptr = head;
        int item = ptr->item;
        head = ptr->next;
        if (NULL == head) tail = head;
        else head->prev = NULL;
        free(ptr);
        ptr = NULL;
        return item;
    }
}

void list()
{
    node *ptr = head;

    while (NULL != ptr) {
        printf("%d ", ptr->item);
        ptr = ptr->next;
    }

    printf("\n");
}

int main(int argc, char *argv[])
{
    for (int i = 1; i <= 10; i++)
        insert(i, i < 6);

    list(); // 10 9 8 7 6 1 2 3 4 5

    for (int i = 1; i <= 4; i++)
        delete(i < 3);

    list(); // 8 7 6 1 2 3
}
~~~

Looking at the implementation above you will notice the omission of the list traversal when removing a node from the tail, as the previous nodes reference is already at hand.
The 'list' method only takes into consideration forward iteration over the list, however, it would be very easy to modify the code to perform backwards traversal.
It is good practice to not only free the memory that you do not require anymore, but also NULL any related pointers, as these pointers still have access to the now unowned memory location.

One C implementation detail I would like to discuss is the use of 'typedef struct' when declaring the node structure.
In C there are two different namespaces for types, a namespace for 'struct' tags and one for 'typedef' names.
Referring to a 'struct' can be very verbose and to get around this we can declare both a node 'struct' and a plain node in the 'typedef' namespace.
These both refer to the same type, and we are then able to omit the 'struct' keyword.
Using only the 'typedef' declaration however, would not allow us to perform [forward declaration](http://en.wikipedia.org/wiki/Forward_declaration), which gives us the ability to use an identifier before giving the compiler the complete definition.

### Resources

- [Difference between 'struct' and 'typedef struct' in C++?](http://stackoverflow.com/questions/612328/difference-between-struct-and-typedef-struct-in-c)
- [Forward declaration](http://en.wikipedia.org/wiki/Forward_declaration)