<?php

use dflydev\markdown\MarkdownExtraParser as MarkdownParser;

define('USE_CACHE', $_SERVER['SERVER_PORT'] != 8080);

define('TMPL_DIR',  '../template/');
define('TMPL_EXT',  '.tmpl.php');
define('CACHE_DIR', '../cache/');
define('POST_DIR',  '../posts/');
define('POST_URL',  'posts/');

require('../vendor/autoload.php');

$markdown = new MarkdownParser();

function cache($key, $content)
{
    if ( ! USE_CACHE) return $content();

    ! is_dir(CACHE_DIR) && mkdir(CACHE_DIR);

    $path = CACHE_DIR . md5($key);

    if ( ! file_exists($path)) {
        $result = $content();

        if ($result !== false) {
            file_put_contents($path, $result);
        } else {
            return false;
        }
    }

    return file_get_contents($path);
}

function tmpl($file, $tmpl = [])
{
    ob_start();

    extract($tmpl);

    eval('?>' . file_get_contents(TMPL_DIR . $file . TMPL_EXT));

    return ob_get_clean();
}

function post($file)
{
    $contents = file_get_contents(POST_DIR . $file);

    preg_match_all('/(.+): (.+)/', $contents, $metaMatches, PREG_SET_ORDER);

    $meta = [];
    foreach ($metaMatches as $match) {
        $meta[trim($match[1])] = trim($match[2]);
    }

    preg_match('/-+\n{2}(.*)/s', $contents, $postMatch);

    if ( ! isset($meta['slug']) || ! isset($postMatch[1])) {
        return false;
    }

    $meta['url'] = POST_DIR . $meta['slug'];

    return [
        'meta' => $meta,
        'post' => trim($postMatch[1])
    ];
}

function posts() {
    foreach (array_reverse(glob(POST_DIR . '*')) as $file) {
        if ($post = post($file)) {
            yield $post;
        }
    }
}

$request = trim($_SERVER['REQUEST_URI'], '/');

$output = cache($request, function() use ($request)
{
    if ($request) {
        foreach (posts() as $post) {
            if (POST_URL . $post['meta']['slug'] == $request) {
                return tmpl('post', $post);
            }
        }

        return false;
    } else {
        return tmpl('main', [ 'posts' => posts() ]);
    }
});

if ($output === false) {
    header('HTTP/1.0 404 Not Found');
    echo tmpl('404');
} else {
    echo $output;
}

exit;