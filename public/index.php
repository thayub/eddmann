<?php

require '../vendor/autoload.php';

$request = trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

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
            if (config('post.url') . $post['meta']['slug'] == $request) {
                $filter = compose('markdown', 'pygments', 'graphviz');
                $post['post'] = $filter($post['post']);
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