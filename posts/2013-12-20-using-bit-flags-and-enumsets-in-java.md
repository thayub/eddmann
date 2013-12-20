---
title: Using Bit Flags and EnumSets in Java
slug: using-bit-flags-and-enumsets-in-java
abstract: Comparison article on the differences when using Bit Flags and EnumSets in Java.
date: 20th Dec 2013
---

### Bit Flags

Bit flags, commonly referred to as Bit fields are an efficient way of storing several related boolean values in a single primitive type.
Internally represented in binary, you can decide on how large the storage type needs to be - for example, a Java integer will provide you with space for 31 flags.
Being a 32 bit type you would assume to have access to this many flags, however, due to it being a signed value the most significant bit is reserved to store its +/- sign.

Flags are typically stored as public constants within the related class, for example, the [Pattern](http://docs.oracle.com/javase/7/docs/api/java/util/regex/Pattern.html) class includes the flags 'CASE_INSENSITIVE' and 'MULTILINE' to alter match criteria.
Setting the values as incremental powers of 2 allows the user to compose multiple constants together using the | (OR) operator to achieve the desired flag combination.
The implementer is then able to base the methods execution on the passed in flags by checking for presence of the constant value using the & (AND) operator as a bit mask.
This idea can be more meaningfully described using the example below.

~~~ .java
public static final int UPPERCASE = 1;  // 0001
public static final int REVERSE   = 2;  // 0010
public static final int FULL_STOP = 4;  // 0100
public static final int EMPHASISE = 8;  // 1000
public static final int ALL_OPTS  = 15; // 1111

public static String format(String value, int flags)
{
    if ((flags & UPPERCASE) == UPPERCASE) value = value.toUpperCase();

    if ((flags & REVERSE) == REVERSE) value = new StringBuffer(value).reverse().toString();

    if ((flags & FULL_STOP) == FULL_STOP) value += ".";

    if ((flags & EMPHASISE) == EMPHASISE) value = "~*~ " + value + " ~*~";

    return value;
}
~~~

As you can see, I have defined 5 different constants all with specific values that correspond to different binary representations.
In regard to the final constant which I set to the decimal 15, in essence this fills in all of the previously created value combinations.
Within the method block I check for presence of each flag by masking the 'flags' parameter with the constant value.
If the resulting value is equal to the specified constant we can then act on this condition.

~~~ .java
format("Joe", UPPERCASE); // JOE

format("Joe", REVERSE); // eoJ

format("Joe", FULL_STOP | EMPHASISE); // ~*~ Joe. ~*~

format("Joe", ALL_OPTS); // ~*~ EOJ. ~*~
~~~

Above are a few examples of the method being used in multiple ways.
The third example shows the ability of composing values from multiple flags (binary representation 1100).

### EmunSets

Traditional use of bit flags has been around for many years and are a very performant storage mechanism (especially in graphic rendering).
However, it can be very easy for the resulting code to be hard to understand.
Another glaring issue is that they are not type safe, nullifying the benefits of having a type system put in place.
[EnumSets](http://docs.oracle.com/javase/7/docs/api/java/util/EnumSet.html) however have the efficiency of bit flags, but without the loss of safety the the type system provides.
I should point out that there is another Set implementation called [BitSet](http://docs.oracle.com/javase/7/docs/api/java/util/BitSet.html) which provides similar functionality.
It is recommended that if you desire a flagging system such as the use-case described an EnumSet will provide you with the best results.

~~~ .no-highlight
public enum Flag {
    UPPERCASE, REVERSE, FULL_STOP, EMPHASISE;

    public static final EnumSet<Flag> ALL_OPTS = EnumSet.allOf(Flag.class);
}
~~~

~~~ .java
public static String format(String value, EnumSet<Flag> flags)
{
    if (flags.contains(Flag.UPPERCASE)) value = value.toUpperCase();

    if (flags.contains(Flag.REVERSE)) value = new StringBuffer(value).reverse().toString();

    if (flags.contains(Flag.FULL_STOP)) value += ".";

    if (flags.contains(Flag.EMPHASISE)) value = "~*~ " + value + " ~*~";

    return value;
}
~~~

In the case of an EnumSet you must first define your enumerated type, which encapsulates all the desired constants.
Similar to how I defined the 'ALL_OPTS' constant in the first example, I have created a EnumSet consisting of all constants in the Flag type.
EnumSet implements the Set interface, so you can use it as you would a typical set, calling 'contains' on it to query presence of a certain flag.

~~~ .java
format("Sally", EnumSet.of(Flag.UPPERCASE)); // SALLY

format("Sally", EnumSet.of(Flag.REVERSE)); // yllaS

format("Sally", EnumSet.of(Flag.FULL_STOP, Flag.EMPHASISE)); // ~*~ Sally. ~*~

format("Sally", Flag.ALL_OPTS); // ~*~ YLLAS. ~*~
~~~

Above are a few examples of using the EnumSet in place of a typical bit flag system.
As you can see, we are able to complete all the examples that were possible in the previous use-case, but this time it is more readable and we are less prone to error.