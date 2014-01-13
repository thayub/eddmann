<?php

require '../vendor/autoload.php';

header('Content-Type: application/xml; charset=utf-8');

echo cache('rss.xml', function()
{
    return tmpl('rss', [ 'posts' => posts() ]);
});

exit;