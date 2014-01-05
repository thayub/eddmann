---
title: Decimal to Hexadecimal using Bitwise, Bit Shift Operations in Java
slug: decimal-to-hexadecimal-using-bitwise-bit-shift-operations-in-java
abstract: Convert between the two rep. to generate a random web colour.
date: 18th Dec 2013
---

I recently wanted to create a simple function in JavaScript which allowed me to generate a random background colour for a small [experiment](http://workshop.eddmann.com/copacabana/) I was working on.
The implementation I came up with worked very well, but the decimal-hexadecimal representation conversion was all wrapped up in one 'toString(16)' function call.
I was very interested in how I could go about creating this method myself and decided on the Java language for the attempt.
The discussed functionality is already present in the Java language to, within the 'java.lang.Integer' class as 'toHexString'.

Hexadecimal uses the positional base system of 16, where each digit can represent four binary digits (bits).
Taking a look at the [documentation](http://docs.oracle.com/javase/tutorial/java/nutsandbolts/datatypes.html) you will see that a Java integer is able to hold a 32bit signed, two's complement value between -2^31 and 2^31-1.
These two findings allow us to deduce that the smallest and largest integer numbers can be represented in a maximum of 8 hexadecimal positions.
The discussed two's compliment is a widely used scheme within computing, using the most significant bit to determine if the value is negative or not.
To convert a negative number to and from a two's complement representation, a simple inversion of each binary bit and addition by one needs to take place.
With this knowledge we can now go about implementing the solution found below.

### Decimal to Hexadecimal

~~~ .java
public static String toHexString(int decimal)
{
    String codes = "0123456789ABCDEF";

    StringBuilder builder = new StringBuilder(8);

    builder.setLength(8);

    for (int i = 7; i >= 0; i--) {
        builder.setCharAt(i, codes.charAt(decimal & 0xF));
        decimal >>= 4;
    }

    return builder.toString();
}
~~~

In the above implementation I first make a 'StringBuilder' instance with the initial size capacity of 8 (instead of the default 16).
I then subsequently set the current length of the instance to the full 8, which pads the string with null values ('\u0000').
Once this is complete I loop over the builder instance starting with the right most character, the lowest position in a positional numeral system.
Within the loop I use a bitwise 'AND' mask which returns the current lowest positioned 4 bits from the subject integer.
I could have alternatively used the decimal '15', or the representation used in the bitwise operation '0b1111'.
A value between 0 and 15 will be returned from the operation which can be looked up in the 'codes' string and set in the builder instance.
To complete a full step through the loop I do a signed right shift on the subject integer which shifts the pattern 4 bits to the right, allowing me to process the next position.
Finally, once the loop has finished I return a string representation of the builder instance.

### Example Usage

~~~ .java
int min = Integer.MIN_VALUE; // -2147483648
int max = Integer.MAX_VALUE; // 2147483647

String minHex = toHexString(min); // 80000000
String maxHex = toHexString(max); // 7FFFFFFF

System.out.printf(
    "valid: min %c, max %c",
    (min == (int) Long.parseLong(minHex, 16) ? '\u2714' : '\u2717'),
    (max == Integer.parseInt(maxHex, 16) ? '\u2714' : '\u2717')
); // valid: min ✔, max ✔
~~~

Above are a couple of examples which show the solution being used to represent the minimum and maximum possible integer values.
One issue that did arise was when converting the minimum value back to a decimal representation.
I was required to do a bewildering parse as a long instead, and then cast back down to the desired integer.
The reason for this is that Java parses integers as signed values, so inserting anything higher than 0x7FFFFFFF will throw an error.
If you parse it as a larger long however, the value will still be signed but the cast will overflow the integer back to it's correct value.

### Random Web Colours

Now back to the reason why I wanted to convert decimal representations into hexadecimal in the first place.
Web colours are represented by a six digit hex-triplet.
Each two bytes represent the Red, Green and Blue components of the particular colour.
With this knowledge, we simply only have to generate a random number between and inclusive of 0 (0x000000) and 16777215 (0xFFFFFF).
We then can convert this decimal representation into hexadecimal using are built implementation.
There are only two points I would like to make about the code below, most importantly the removal of the the first two characters of the string returned.
This is due to hex-triplets only using 6 positions, which means the most significant two are not required.
Also Java's 'nextInt' method is inclusive of 0 and exclusive of the provided maximum value, so we must add one to the desired range to return a correct result.

~~~ .java
public static String randomWebColour()
{
    return "#" + toHexString(new java.util.Random().nextInt(16777216)).substring(2);
}
~~~

### Resources

- [Primitive Data Types - Java Documentation](http://docs.oracle.com/javase/tutorial/java/nutsandbolts/datatypes.html)
- [Bitwise and Bit Shift Operators - Java Documentation](http://docs.oracle.com/javase/tutorial/java/nutsandbolts/op3.html)
- [Two's Complement Tutorial](http://www.cs.cornell.edu/~tomf/notes/cps104/twoscomp.html)
- [Java negative int to hex and back fails](http://stackoverflow.com/questions/845230/java-negative-int-to-hex-and-back-fails)