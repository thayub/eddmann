var tf = new TweetFetcher('354188847791366144');

tf.fetch(function(tweets)
{
    for (var output = '', i = 0, l = tweets.length; i < l; i++) {
        output +=
            '<div class="tweet col span_12">' +
                '<p class="msg">' + tweets[i].content + '</p>' +
                '<p class="meta">' + tweets[i].time.pretty + '</p>' +
            '</div>';
    }
    document.getElementById('tweets').innerHTML = output;
}, 2);

for (var links = document.getElementsByTagName('a'), i = 0, l = links.length; i < l; i++) {
    var link = links[i];
    if (link.getAttribute('href') && link.hostname !== location.hostname)
        link.target = '_blank';
}

hljs.initHighlightingOnLoad();