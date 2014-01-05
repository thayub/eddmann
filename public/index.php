<?php

use \Michelf\MarkdownExtra as Markdown;

define('USE_CACHE', $_SERVER['SERVER_PORT'] != 8080);

define('TMPL_DIR',  '../template/');
define('TMPL_EXT',  '.tmpl.php');
define('CACHE_DIR', '../cache/');
define('POST_DIR',  '../posts/');
define('POST_URL',  'posts/');
define('PER_PAGE',  9);

require('../vendor/autoload.php');

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
    preg_match('/^-+\n(.+)\n-+\n+(.+)$/s', file_get_contents(POST_DIR . $file), $contents);

    if (count($contents) != 3) {
        return false;
    }

    preg_match_all('/(.+): (.+)/', $contents[1], $metaMatches, PREG_SET_ORDER);

    $meta = [];
    foreach ($metaMatches as $match) {
        $meta[trim($match[1])] = trim($match[2]);
    }

    if ( ! isset($meta['slug'])) {
        return false;
    }

    $meta['url'] = '/' . POST_URL . $meta['slug'] . '/';

    return [
        'meta' => $meta,
        'post' => trim($contents[2])
    ];
}

function posts()
{
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(POST_DIR), RecursiveIteratorIterator::SELF_FIRST
    );

    foreach (array_reverse(iterator_to_array($files)) as $file) {
        if ($post = post($file)) {
            yield $post;
        }
    }
}

function page($page, $limit = PER_PAGE)
{
    $start = ($page - 1) * $limit;
    $end   = $start + $limit;

    foreach (posts() as $i => $post) {
        if ($i >= $end) yield true;

        if ($i >= $start && $i < $end) {
            yield $post;
        }
    }
}

function pygments($post)
{
    return preg_replace_callback('/~~~[\s]?.([a-z]+)\n(.*?)\n~~~/s', function($match)
    {
        list($orig, $lang, $code) = $match;

        $proc = proc_open(
            'pygmentize -f html -O style=default,encoding=utf-8,startinline -l ' . $lang,
            [ [ 'pipe', 'r' ], [ 'pipe', 'w' ], [ 'pipe', 'w' ] ],
            $pipes
        );

        fwrite($pipes[0], $code);
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        return (proc_close($proc))
            ? $orig
            : $output;
    }, $post);
}

$request = trim($_SERVER['REQUEST_URI'], '/');

if (preg_match('/^page\/[1-9][0-9]*$/', $request)) {
    $page = (int) explode('/', $request)[1];
    $isPage = true;
} else {
    $page = 1;
    $isPage = false;
}

$output = cache($request, function() use ($request, $page, $isPage)
{
    if ($request && ! $isPage) {
        foreach (posts() as $post) {
            if (POST_URL . $post['meta']['slug'] == $request) {
                $post['post'] = Markdown::defaultTransform(pygments($post['post']));
                return tmpl('post', $post);
            }
        }
    } else {
        $posts = page($page);

        if ($posts->key() !== null) {
            return tmpl('main', [ 'posts' => $posts, 'page' => $page ]);
        }
    }

    return false;
});

if ($output === false) {
    header('HTTP/1.0 404 Not Found');
    echo tmpl('404');
} else {
    echo $output;
}

exit;