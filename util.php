<?php

function config($key = '')
{
    static $config = null;

    if ($config === null) {
        $config = require 'config.php';
    }

    $parsed = explode('.', $key);

    $result = $config;

    while ($parsed) {
        $result = $result[array_shift($parsed)];
    }

    return $result;
}

function cache($key, callable $value)
{
    if ( ! config('live')) return $value();

    ! is_dir(config('cache.dir')) && mkdir(config('cache.dir'));

    $path = config('cache.dir') . md5($key);

    if ( ! file_exists($path)) {
        $result = $value();

        if ($result !== false) {
            file_put_contents($path, $result);
        } else {
            return false;
        }
    }

    return file_get_contents($path);
}

function tmpl($file, $vars = [])
{
    ob_start();

    extract($vars);

    eval('?>' . file_get_contents(config('tmpl.dir') . $file . config('tmpl.ext')));

    return ob_get_clean();
}

function compose(/* $func... */)
{
    $func = func_get_args();

    $output = 'return ';

    foreach ($func as $f) {
        $output .= $f . '(';
    }

    $output .= '$x' . str_repeat(')', count($func)) . ';';

    return create_function('$x', $output);
}