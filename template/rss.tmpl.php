<?php

$xml = new DOMDocument('1.0', 'utf-8');
$root = $xml->appendChild($xml->createElement('rss'));
$root->setAttribute('version', '2.0');

$chan = $root->appendChild($xml->createElement('channel'));
$chan->appendChild($xml->createElement('title', 'edd mann • software developer'));
$chan->appendChild($xml->createElement('link', 'http://eddmann.com/'));
$chan->appendChild($xml->createElement('description', 'I make stuff for the web, and occasionally ramble about it here.'));
$chan->appendChild($xml->createElement('lastBuildDate', date(DATE_RSS)));

foreach ($posts as $post)
{
    $item = $chan->appendChild($xml->createElement('item'));
    $item->appendChild($xml->createElement('title', str_replace('&', '&amp;', $post['meta']['title'])));
    $item->appendChild($xml->createElement('link', 'http://eddmann.com/' . config('post.url') . $post['meta']['slug'] . '/'));
    $item->appendChild($xml->createElement('description', str_replace('&', '&amp;', $post['meta']['abstract'])));
    $item->appendChild($xml->createElement('guid', $post['meta']['url'] . '/'));
    $item->appendChild($xml->createElement('pubDate', date(DATE_RSS, strtotime($post['meta']['date']))));
}

$xml->formatOutput = true;

echo $xml->saveXML();