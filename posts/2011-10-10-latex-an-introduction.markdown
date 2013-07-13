---
title: LaTeX, an introduction
slug: latex-an-introduction
---

LaTeX (pronounced *lay-tech*) is a document preparation system providing high quality typesetting, using the [WYSIWYM](http://en.wikipedia.org/wiki/WYSIWYM) ideology.
Based on Donald E. Knuths TeX typesetting language, LaTeX was developed in 1985 by Leslie Lamport and has become a staple in areas such as academia.
It has also been found to be the required text processing language for many periodicals.

<figcaption>Below are some of the key features of LaTeX</figcaption>

1. Focuses the author on the content not presentation, separation of concerns
2. Handles complex mathematical equations with great ease
3. Superior vector graphic (*.svg*) handling
4. Professional font handling/kerning
5. Automatic generation of bibliographies, table of contents, figures etc.
6. Platform/application agnostic - due to being plain text files

One key aspect that must be mentioned is that LaTeX is not a word processor, but rather a markup language - which may make it more appealing to developers.
This means that it doesn't require mandatory software to be created or modified.
All complying documents are plain text files (*.tex*) which can be viewed/altered with any plain text editor (such as VIM, Emacs).
These markup text files can then be processed using LaTeX into many formats such as device output files (*.dvi*) or Adobe portable documents (*.pdf*).

### Installation

There are installation packages available for all major platforms.
If you are using a Mac an installer can be found at [MacTex](http://www.tug.org/mactex/2011/) - also bundling TeXShop, which I have found to be great for quick experimenting while learning LaTeX.
Users on the Windows platform can head to [proTeXt](http://www.tug.org/protext/) which follows a similar simple installation process as its Mac variant.

<figure>
    <figcaption>A screenshot of TeXShop in action</figcaption>
    <img src="/assets/latex-an-introduction/tex-shop.png" />
</figure>

### Basics

Creating a simple LaTeX document couldn't be any simpler.
Similar to how you have to structure a HTML document, a basic LaTeX structure is required.

    \documentclass{article}
    \begin{document}
    Hello LaTeX!
    \end{document}

In a few lines we have been able to create a document which can be easily processed using LaTeX into a variety of formats.
The first line is used to tell LaTeX how to format the document (heading, spacing, etc.).
There are many different document formats available to you, ranging from *minimal* to *book* - though further explanation is a little overwhelming for an introductory article.
The final block of code that is defined in the code sample is the document contents.
LaTeX locates this during processing and deems that the content inside to be the document to be processed.

    $ pdflatex hello.tex

Finally to generate the sample document as a PDF article the simple line above must be executed in the terminal.
The resulting PDF from executing this action is available [here](/assets/latex-an-introduction/latex-basic.pdf).

### More Advanced

Now that we are familiar with the basic process of creating and generating output from a LaTeX document, we can begin to use some of the more powerful features available to us.
As I explained at the beginning of this article, LaTeX provides you with a toolkit full of goodies that ease in the creation of both large and small documents (of varying types);

    \documentclass{article}
    \usepackage{graphicx}
    \begin{document}

    \begin{center}
      \includegraphics[width=3cm]{latex-logo.png}
    \end{center}

    \begin{tabular}{ | p {3cm} | p{5cm} | }
      \hline
      \textbf{Player} & \textbf{Position} \\ \hline
      LeBron James & Small Forward \\ \hline
      Dwayne Wade & Point Guard \\
      \hline
    \end{tabular}

    \end{document}

The above example introduces multiple features available to you (outputted result available [here](/assets/latex-an-introduction/latex-advanced.pdf)), ranging from including a resized image to a formatted table.
Reading through the example you will notice that the first difference from the first code snippet is the use of packages.
To expand the functionality of LaTeX (in a modular way) you are able to import different desired packages for use in your document.
The package that we are importing allows us, as the name suggests, to import and display graphics.
As well as displaying the graphic, I highlighted before that we have also defined the desired width that the image should be.
Many metrics can be used (*mm*, *cm*, *pt*) to define the size whilst also maintaining the images aspect ratio.
The formatted table that is displayed shows two columns with three rows.
The column widths are set during the declaration of the table and <span class="snippet">\\</span> is used to signify that a rows contents has ended.
Other notable syntax that is being used is the call of <span class="snippet">\textbf</span> to make the column titles bold, <span class="snippet">&amp;</span> used to split the row contents into columns and <span class="snippet">\hline</span> to tell LaTeX to print a horizontal line.

### Wrap Up

I hope that this article has provided enough information to help you begin using LaTeX, and maybe convinced you to delve a little deeper into its many powerful features.
If you are looking for a more in-depth overview of indivdual aspects of LaTeX, use the resources provided below as a good starting point.
On top of that you are welcome to have a look at a heavily commented VIM cheat sheet I have created using many of LaTeX's prominent features.
This document is available [here](http://github.com/eddmann/vim-cheat-sheet) at my GitHub account and forking/modification is highly encouraged.

### Resources

* [LaTeX - Absolute Beginners](http://en.wikibooks.org/wiki/LaTeX/Absolute_Beginners)
* [Getting Started with LaTeX](http://www.maths.tcd.ie/~dwilkins/LaTeXPrimer/)
* [LaTeX based VIM Cheat Sheet](http://github.com/eddmann/vim-cheat-sheet)
* [MacTeX](http://www.tug.org/mactex/2011/)
* [proTeXt](http://www.tug.org/protext/)