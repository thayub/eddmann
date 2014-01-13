<?php

function post($file)
{
    preg_match('/^-+\n(.+)\n-+\n+(.+)$/s', file_get_contents($file), $contents);

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

    $meta['url'] = '/' . config('post.url') . $meta['slug'] . '/';

    return [
        'meta' => $meta,
        'post' => trim($contents[2])
    ];
}

function posts()
{
    foreach (array_reverse(glob(config('post.dir') . '*/*')) as $file) {
        if ($post = post($file)) {
            yield $post;
        }
    }
}

function page($page)
{
    $start = ($page - 1) * config('post.per');
    $end   = $start + config('post.per');

    foreach (posts() as $i => $post) {
        if ($i >= $end) yield true;

        if ($i >= $start && $i < $end) {
            yield $post;
        }
    }
}

function markdown($post)
{
    return \Michelf\MarkdownExtra::defaultTransform($post);
}

function pygments($post)
{
    return preg_replace_callback('/~~~[\s]*\.([a-z]+)\n(.*?)\n~~~/is', function($match)
    {
        list($orig, $lang, $code) = $match;

        $proc = proc_open(
            'pygmentize -f html -O style=default,encoding=utf-8,startinline -l ' . $lang,
            [ [ 'pipe', 'r' ], [ 'pipe', 'w' ] /* ignore stderr */ ],
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

function graphviz($post)
{
    return preg_replace_callback('/~~~[\s]*\.dot-show\n(.*?)\n~~~/is', function($match)
    {
        list($orig, $dot) = $match;

        $proc = proc_open(
            'dot -Tsvg',
            [ [ 'pipe', 'r' ], [ 'pipe', 'w' ] /* ignore stderr */ ],
            $pipes
        );

        fwrite($pipes[0], $dot);
        fclose($pipes[0]);

        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        if ( ! proc_close($proc)) {
            $output = preg_replace('/.*<svg width="[0-9]+pt" height="([0-9]+pt)"/s', '<svg style="max-height:$1;" ', $output);
            $output = preg_replace('/<!--(.*)-->/Uis', '', $output);
            $output = preg_replace('/id="(.*?)"/s', 'id="$1_' . rand() . '"', $output);
        } else {
            $output = $orig;
        }

        return $output;
    }, $post);
}