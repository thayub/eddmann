<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>edd mann • software developer</title>

    <link rel="stylesheet" href="/assets/css/styles.min.css">

    <!--                                           _             _
     _ __   ___  ___  ___ _   _   _ __   __ _ _ __| | _____ _ __| |
    | '_ \ / _ \/ __|/ _ \ | | | | '_ \ / _` | '__| |/ / _ \ '__| |
    | | | | (_) \__ \  __/ |_| | | |_) | (_| | |  |   <  __/ |  |_|
    |_| |_|\___/|___/\___|\__, | | .__/ \__,_|_|  |_|\_\___|_|  (_)
                          |___/  |_|
    -->

    <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
    <header>
        <div class="container row">
            <h1 class="col span_12"><a href="/">edd <span>&#10026;</span> mann</a></h1>
            <nav class="col span_12">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="http://twitter.com/edd_mann" target="_blank">Twitter</a></li>
                    <li><a href="http://github.com/eddmann" target="_blank">GitHub</a></li>
                    <li><a href="/EdwardMannProgrammerCV.pdf">CV</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <section id="content">
        <h3 class="tagline"><span>I make stuff for the web</span> and occasionally ramble about it here.</h3>
        <div class="container row">
        <?php $break = 0; foreach ($tmpl['posts'] as $post): ?>
            <div class="post col span_8">
                <h4><a href="<?php echo $post['meta']['url']; ?>"><?php echo $post['meta']['title']; ?></a></h4>
                <p><?php echo $post['meta']['abstract']; ?></p>
            </div>
            <?php if ($break++ == 2): ?>
                </div>
                <div class="container row">
            <?php endif; ?>
        <?php $break %= 3; endforeach; ?>
        </div>
    </section>
    <footer>
        <section id="latest-tweets">
            <div id="tweets" class="container row"></div>
        </section>
        <section id="details">
            <div id="details-wrapper">
                <img src="/assets/img/me.png" id="me" alt="Me..." title="Me...">
                <ul id="contact">
                    <li><span>twitter</span> <a href="http://twitter.com/edd_mann" target="_blank">@edd_mann</a></li>
                    <li><span>github</span> <a href="http://github.com/eddmann" target="_blank">eddmann</a></li>
                    <li><span>email</span> <a href="mailto:the@eddmann.com">the@eddmann.com</a></li>
                </ul>
            </div>
        </section>
    </footer>
    <script>
        var _gaq=[['_setAccount','UA-32512081-1'],['_setDomainName','eddmann.com'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
    <script src="/assets/js/scripts.min.js"></script>
</body>
</html>