---
title: Implementing a Dynamic Vector (Array) in C
slug: implementing-a-dynamic-vector-array-in-c
abstract: Implementing a resizable, dynamic array data-type in C.
date: 20th Jan 2014
---

An array (vector) is a common-place data type, used to hold and describe a collection of elements.
These elements can be fetched at runtime by one or more indices (identifying keys).
A distinguishing feature of an array compared to a list is that they allow for constant-time random access lookup, compared to the latters sequential access.
Resizable arrays allow for an unspecified upper-bound of collection elements at runtime, and are conceptuality similar to a list.
These dynamic arrays are more complicated and less used in introduction to its compatriot list, which is dynamic by nature.
Using C as the language of implementation this post will guide you through building a simple vector data-structure.
The structure will take advantage of a fixed-size array, with a counter invariant that keeps track of how many elements are currently present.
If the underlying array becomes exhausted, the addition operation will re-allocate the contents to a larger size, by way of a copy.

### The Make File

'Make' is a popular utility used throughout software development to build executable artifacts (programs and libraries) from described source code.
Through a simple DSL, associations from descriptive short-names (targets) and a series of related commands to execute are made.
Running the 'make' command executes the first present target, and this must be considered in the design of the file.
Below is a sample Makefile which provides the vector project with simple build, debug and clean targets.

~~~ .make
CC=gcc
CFLAGS=
RM=rm -rf
OUT=vector

all: build

build: main.o vector.o
    $(CC) $(CFLAGS) -o $(OUT) main.c vector.c
    $(RM) *.o

debug: CFLAGS+=-DDEBUG_ON
debug: build

main.o: main.c vector.h
    $(CC) $(CFLAGS) -c main.c

vector.o: vector.c vector.h
    $(CC) $(CFLAGS) -c vector.c

clean:
    $(RM) *.o $(OUT)
~~~

Looking at the code example above you will notice a few variables which are used to define specific aspects used when running the targets (such as the compiler command and flags used).
To keep things modular the compilation of the 'main' and 'vector' source-code files has been split, with file dependences specific to each target specified after the short-name.
The 'debug' target appends a macro definition flag which is used to include any debug information present in the source code.

### The Header File

Defining a header file allows the programmer to separate specific aspects of the programs source-code into reusable files.
These files commonly contain forward declarations of identifiers and functions.
This allows a user to include the codes header file in their own work, separating the definition from the implementation.
Including a header file produces the same results as copying the full contents into the callers file.
Below shows the header file implemented for the vector example.

~~~ .c
#ifndef VECTOR_H
#define VECTOR_H

#define VECTOR_INIT_CAPACITY 4

#define VECTOR_INIT(vec) vector vec; vector_init(&vec)
#define VECTOR_ADD(vec, item) vector_add(&vec, (void *) item)
#define VECTOR_SET(vec, id, item) vector_set(&vec, id, (void *) item)
#define VECTOR_GET(vec, type, id) (type) vector_get(&vec, id)
#define VECTOR_DELETE(vec, id) vector_delete(&vec, id)
#define VECTOR_TOTAL(vec) vector_total(&vec)
#define VECTOR_FREE(vec) vector_free(&vec)

typedef struct vector {
    void **items;
    int capacity;
    int total;
} vector;

void vector_init(vector *);
int vector_total(vector *);
static void vector_resize(vector *, int);
void vector_add(vector *, void *);
void vector_set(vector *, int, void *);
void *vector_get(vector *, int);
void vector_delete(vector *, int);
void vector_free(vector *);

#endif
~~~

We wrap the contents of this file in a definition condition to make sure that even with multiple inclusion between aggregate source code files, only one inclusion is processed in the result.
A 'vector' type definition is included which provides access to the capacity and total current elements in the collection.
Along with this, a 'items' variable with a pointer of void pointers is included, allowing us to insert a heterogeneous collection of elements into the vector.
The 'vector_resize' method is defined to be 'static' resulting in successful execution of the function only occurring in the file it is defined in (accessibility control).

### The Implementation File

Using the header file definition, the following file is used to implement these methods.
As discussed in the previous section 'void pointers' are used to reference the collection elements.
Void pointers are pointers which point to some arbitrary data that has no specific type.
As a consequence you are unable to directly deference a pointer of this type and must first provide a casting type.

~~~ .c
#include <stdio.h>
#include <stdlib.h>

#include "vector.h"

void vector_init(vector *v)
{
    v->capacity = VECTOR_INIT_CAPACITY;
    v->total = 0;
    v->items = malloc(sizeof(void *) * v->capacity);
}

int vector_total(vector *v)
{
    return v->total;
}

static void vector_resize(vector *v, int capacity)
{
    #ifdef DEBUG_ON
    printf("vector_resize: %d to %d\n", v->capacity, capacity);
    #endif

    void **items = realloc(v->items, sizeof(void *) * capacity);
    if (items) {
        v->items = items;
        v->capacity = capacity;
    }
}

void vector_add(vector *v, void *item)
{
    if (v->capacity == v->total)
        vector_resize(v, v->capacity * 2);
    v->items[v->total++] = item;
}

void vector_set(vector *v, int index, void *item)
{
    if (index >= 0 && index < v->total)
        v->items[index] = item;
}

void *vector_get(vector *v, int index)
{
    if (index >= 0 && index < v->total)
        return v->items[index];
    return NULL;
}

void vector_delete(vector *v, int index)
{
    if (index < 0 || index >= v->total)
        return;

    v->items[index] = NULL;

    for (int i = 0; i < v->total - 1; i++) {
        v->items[i] = v->items[i + 1];
        v->items[i + 1] = NULL;
    }

    v->total--;

    if (v->total > 0 && v->total == v->capacity / 4)
        vector_resize(v, v->capacity / 2);
}

void vector_free(vector *v)
{
    free(v->items);
}
~~~

Looking at the code example above you will notice that the 'vector_resize' function is called if certain conditions are met on addition or deletion.
If the current vector capacity has been exhausted when an addition has been requested the size is doubled and the vector contents re-allocated.
In a similar fashion, upon deletion, if the vector is a quarter full the contents is reallocated to a vector of half the current size.
These conditions for resizing work well in practice to balance memory capacity and computation time required to fulfill each resize.

### The Test Case

With all the pieces put in place we are now able to test case the implementation.
Below shows an example using the direct functions, adding a few strings (character sequences) to a collection, printing the contents, modifying the contents and then printing it out again.
One unfortunate use-case detail that can not be avoided with the use of void pointers is the necessary cast.

~~~ .c
#include <stdio.h>
#include <stdlib.h>

#include "vector.h"

int main(void)
{
    int i;

    vector v;
    vector_init(&v);

    vector_add(&v, "Bonjour");
    vector_add(&v, "tout");
    vector_add(&v, "le");
    vector_add(&v, "monde");

    for (i = 0; i < vector_total(&v); i++)
        printf("%s ", (char *) vector_get(&v, i));
    printf("\n");

    vector_delete(&v, 3);
    vector_delete(&v, 2);
    vector_delete(&v, 1);

    vector_set(&v, 0, "Hello");
    vector_add(&v, "World");

    for (i = 0; i < vector_total(&v); i++)
        printf("%s ", (char *) vector_get(&v, i));
    printf("\n");

    vector_free(&v);
}
~~~

To simplify the use of the vector implementation the header file defines a few macro functions which can be used in place of the base function calls.
Below highlights these definition in practice, removing some of the verbosity present in the previous example.

~~~ .c
#include <stdio.h>
#include <stdlib.h>

#include "vector.h"

int main(void)
{
    int i;

    VECTOR_INIT(v);

    VECTOR_ADD(v, "Bonjour");
    VECTOR_ADD(v, "tout");
    VECTOR_ADD(v, "le");
    VECTOR_ADD(v, "monde");

    for (i = 0; i < VECTOR_TOTAL(v); i++)
        printf("%s ", VECTOR_GET(v, char*, i));
    printf("\n");

    VECTOR_DELETE(v, 3);
    VECTOR_DELETE(v, 2);
    VECTOR_DELETE(v, 1);

    VECTOR_SET(v, 0, "Hello");
    VECTOR_ADD(v, "World");

    for (i = 0; i < VECTOR_TOTAL(v); i++)
        printf("%s ", VECTOR_GET(v, char*, i));
    printf("\n");

    VECTOR_FREE(v);
}
~~~

Despite still having to provide a casting data type when retrieving a collection element, the macros clean-up and simplify the process a great deal.

### Resources

- [Why use Pointers? Dynamic Memory Allocation](http://www.sparknotes.com/cs/pointers/whyusepointers/section3.rhtml)
- [Void Pointers in C](http://www.circuitstoday.com/void-pointers-in-c)
- [Implementation of a Vector data structure in C](http://codingrecipes.com/implementation-of-a-vector-data-structure-in-c)
- [What does "static" mean in a C program?](http://stackoverflow.com/questions/572547/what-does-static-mean-in-a-c-program)