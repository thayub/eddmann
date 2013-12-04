var TweetFetch = function(id)
{
    this.id = id;
    this.instance = 'tf_' + (new Date().getTime());
    window[this.instance] = this;
};

TweetFetch.prototype.fetch = function(cb, limit)
{
    this.cb = cb;
    this.limit = limit || 20;

    var s = document.createElement('script');
    s.src = '//cdn.syndication.twimg.com/widgets/timelines/' + encodeURIComponent(this.id) +
            '?&lang=en&callback=' + encodeURIComponent(this.instance + '.parse') + '&suppress_response_codes=true' +
            'rnd=' + new Date().getTime();
    document.getElementsByTagName('head')[0].appendChild(s);
};

TweetFetch.prototype.parse = function(res)
{
    var raw = document.createElement('div');
    raw.innerHTML = res.body;

    var tweets = raw.querySelectorAll('li.tweet'),
        limit  = (tweets.length < this.limit) ? tweets.length : this.limit,
        output = [];

    for (var i = 0; i < limit; i++) {
        var tr = tweets[i], t = {};

        t.id = tr.getAttribute('data-tweet-id');
        t.author = {
            handle: tr.querySelector('span.p-nickname b').innerText,
            name: tr.querySelector('span.p-name').innerText
        };
        t.time = {
            stamp: tr.querySelector('time.dt-updated').getAttribute('datetime'),
            pretty: tr.querySelector('time.dt-updated').getAttribute('aria-label')
        }
        t.content = tr.querySelector('p.e-entry-title').innerHTML.replace(/<\/?b[^>]*>/gi, '');
        t.isRetweet = !! tr.querySelector('.retweet-credit');
        t.retweets = tr.querySelector('span.stats-retweets')
            ? parseInt(tr.querySelector('span.stats-retweets').innerText.replace(',', ''), 10)
            : 0;

        output.push(t);
    }

    this.cb(output);
};